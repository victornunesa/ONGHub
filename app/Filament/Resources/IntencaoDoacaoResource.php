<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IntencaoDoacaoResource\Pages;
use App\Filament\Resources\IntencaoDoacaoResource\RelationManagers;
use App\Models\Doacao;
use App\Models\Estoque;
use App\Models\IntencaoDoacao;
use App\Models\Ong;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Set;

class IntencaoDoacaoResource extends Resource
{
    protected static ?string $model = IntencaoDoacao::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $modelLabel = 'Intenções';
    protected static ?string $navigationLabel = 'Intenções de doação';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Formulário vazio ou com campos específicos se necessário
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome_solicitante')
                    ->label('Doador')
                    ->searchable(),

                TextColumn::make('email_solicitante')
                    ->label('E-mail')
                    ->searchable(),

                TextColumn::make('descricao')
                    ->label('Item')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column) {
                        return $column->getState();
                    }),

                TextColumn::make('quantidade')
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->unidade)
                    ->label('Qtd da intenção'),

                TextColumn::make('quantidade_recebida')
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->unidade)
                    ->label('Qtd recebida'),

                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'Registrada' => 'gray',
                        'Recebida' => 'success',
                        'Recebida em parte' => 'warning',
                        'Cancelada' => 'danger'
                    }),

                TextColumn::make('data_pedido')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                // Filtros opcionais
            ])
            ->actions([
                Action::make('receber')
                    ->label('Receber')
                    ->icon('heroicon-s-check-circle')
                    ->visible(fn ($record) => $record->status === 'Registrada')
                    ->form([
                        Forms\Components\Repeater::make('itens_recebidos')
                            ->label('Itens Recebidos')
                            ->schema([
                                Forms\Components\TextInput::make('nome_item')
                                    ->label('Nome do Item')
                                    ->datalist(
                                        \App\Models\Estoque::where('ong_id', auth()->user()->ong->id)
                                            ->pluck('nome_item')
                                            ->unique()
                                            ->values()
                                            ->toArray()
                                    )
                                    ->default(fn ($record) => $record->descricao)
                                    ->required()
                                    ->helperText('Digite o nome do item.'),

                                Forms\Components\TextInput::make('quantidade_recebida')
                                    ->label('Quantidade Recebida')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(fn ($record) => $record->quantidade)
                                    ->required(),

                                Forms\Components\Select::make('unidade_recebida')
                                    ->label('Unidade de Recebimento')
                                    ->options([
                                        'kg' => 'Quilograma (kg)',
                                        'g' => 'Grama (g)',
                                        'L' => 'Litro (L)',
                                        'un' => 'Unidade (un)'
                                    ])
                                    ->default(fn ($record) => $record->unidade)
                                    ->required(),

                                Forms\Components\DatePicker::make('data_validade')
                                    ->label('Data de Validade')
                                    ->required()
                                    ->minDate(today())
                                    ->helperText(fn ($state) =>
                                        $state === today()->format('Y-m-d')
                                            ? '⚠️ ATENÇÃO: Este item vence hoje!'
                                            : 'Informe a data de validade deste lote'
                                    )
                                    ->rule('after_or_equal:today'),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('Adicionar outro lote')
                            ->reorderable(false)
                            // ->grid(2)
                            ->minItems(1)
                            ->maxItems(10)
                            ->helperText('Adicione diferentes lotes com prazos de validade distintos'),
                    ])
                    ->action(function ($record, array $data) {
                        \DB::transaction(function () use ($record, $data) {
                            // Evita duplicação de doação
                            if (Doacao::where('intencao_id', $record->id)->exists()) {
                                Notification::make()
                                    ->title('Atenção')
                                    ->body('Esta doação já foi registrada anteriormente')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            try {
                                $totalQuantidadeRecebida = 0;

                                foreach ($data['itens_recebidos'] as $item) {
                                    // Padroniza o nome do item: trim, lowercase, capitalize first letter
                                    $nomeItemPadronizado = ucfirst(strtolower(trim($item['nome_item'])));
                                    $quantidade = $item['quantidade_recebida'];
                                    $totalQuantidadeRecebida += $quantidade;

                                    // 1. Criar movimentação na tabela doacao para cada lote
                                    Doacao::create([
                                        'intencao_id' => $record->id,
                                        'nome_doador' => $record->nome_solicitante,
                                        'email_doador' => $record->email_solicitante,
                                        'telefone_doador' => $record->telefone_solicitante,
                                        'descricao' => $nomeItemPadronizado,
                                        'quantidade' => $quantidade,
                                        'unidade' => $item['unidade_recebida'],
                                        'data_validade' => $item['data_validade'],
                                        'data_doacao' => now(),
                                        'status' => 'Entrada',
                                        'ong_destino_id' => auth()->user()->ong->id,
                                        'ong_origem_id' => null
                                    ]);

                                    // 2. Atualizar estoque para cada lote (cria registro separado para cada validade)
                                    Estoque::create([
                                        'ong_id' => auth()->user()->ong->id,
                                        'nome_item' => $nomeItemPadronizado,
                                        'unidade' => $item['unidade_recebida'],
                                        'quantidade' => $quantidade,
                                        'data_validade' => $item['data_validade'],
                                        'data_atualizacao' => now(),
                                        'quantidade_solicitada' => 0,
                                    ]);
                                }

                                // Atualiza status do pedido
                                if ($totalQuantidadeRecebida < $record->quantidade) {
                                    $record->update(['status' => 'Recebida em parte']);
                                } else {
                                    // 3. Atualizar status da intenção
                                    $record->update([
                                        'status' => 'Recebida',
                                        'unidade' => $data['itens_recebidos'][0]['unidade_recebida'], // Usa a unidade do primeiro item
                                    ]);
                                }

                                Notification::make()
                                    ->title('Doação registrada com sucesso!')
                                    ->body(count($data['itens_recebidos']) . ' lote(s) registrado(s) no estoque.')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Erro ao registrar doação')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                                throw $e;
                            }
                        });
                    })
                    ->modalWidth('lg') // Aumentei para lg para melhor visualização
                    ->modalHeading(fn ($record) => 'Receber: ' . $record->descricao)
                    ->modalDescription('Registre os lotes recebidos com suas respectivas validades')
                    ->after(fn () => \Filament\Actions\Action::make('redirect')->url(IntencaoDoacaoResource::getUrl('index'))),

                Action::make('cancelar')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancelar intenção de doação')
                    ->modalDescription('Tem certeza que deseja cancelar esta intenção? Esta ação não poderá ser desfeita.')
                    ->visible(fn ($record) => $record->status === 'Registrada')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'Cancelada',
                        ]);

                        Notification::make()
                            ->title('Intenção de doação cancelada')
                            ->body('A intenção foi cancelada com sucesso.')
                            ->success()
                            ->send();
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Ações em massa se necessário
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relacionamentos se necessário
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIntencaoDoacaos::route('/'),
            'edit' => Pages\EditIntencaoDoacao::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check() && auth()->user()->ong) {
            $query->where('ong_desejada', auth()->user()->ong->id);
        } else {
            $query->whereNull('id');
        }

        return $query;
    }
}
