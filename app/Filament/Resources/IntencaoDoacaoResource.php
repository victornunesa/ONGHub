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
                    ->label('Quantidade'),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Registrada' => 'gray',
                        'Recebida' => 'success',
                        'Cancelada' => 'danger',
                        default => 'warning'
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
                    ->label('Receber Doação')
                    ->icon('heroicon-s-check-circle')
                    ->visible(fn ($record) => $record->status === 'Registrada')
                    ->form([
                        Forms\Components\TextInput::make('quantidade_recebida')
                            ->label('Quantidade Recebida')
                            ->numeric()
                            ->minValue(0.1)
                            ->default(fn ($record) => $record->quantidade)
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
                    ])
                    ->action(function ($record, array $data) {
                        \DB::transaction(function () use ($record, $data) {
                            // Verifica se já existe registro para esta intenção
                            if (Doacao::where('intencao_id', $record->id)->exists()) {
                                Notification::make()
                                    ->title('Atenção')
                                    ->body('Esta doação já foi registrada anteriormente')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            try {
                                // 1. Registrar a movimentação na tabela doacao
                                Doacao::create([
                                    'intencao_id' => $record->id,
                                    'nome_doador' => $record->nome_solicitante,
                                    'email_doador' => $record->email_solicitante,
                                    'telefone_doador' => $record->telefone_solicitante,
                                    'descricao' => $record->descricao,
                                    'quantidade' => $data['quantidade_recebida'],
                                    'unidade' => $data['unidade_recebida'],
                                    'data_doacao' => now(),
                                    'status' => 'Entrada',
                                    'ong_destino_id' => auth()->user()->ong->id,
                                    'ong_origem_id' => null
                                ]);

                                // 2. Atualizar o estoque
                                $itemEstoque = Estoque::firstOrNew([
                                    'ong_id' => auth()->user()->ong->id,
                                    'nome_item' => $record->descricao,
                                    'unidade' => $data['unidade_recebida']
                                ]);
                                
                                $itemEstoque->quantidade += $data['quantidade_recebida'];
                                $itemEstoque->data_atualizacao = now();
                                $itemEstoque->save();

                                // 3. Atualizar a intenção de doação
                                $record->update([
                                    'status' => 'Recebida',
                                    'quantidade' => $data['quantidade_recebida'],
                                    'unidade' => $data['unidade_recebida']
                                ]);
                                
                                Notification::make()
                                    ->title('Doação registrada com sucesso!')
                                    ->body('Estoque atualizado e movimentação registrada.')
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
                    ->modalWidth('md')
                    ->modalHeading(fn ($record) => 'Receber: ' . $record->descricao)
                    ->modalDescription('Confirme os detalhes da doação recebida')
                    ->after(fn () => \Filament\Actions\Action::make('redirect')->url(IntencaoDoacaoResource::getUrl('index'))),
                
                Action::make('cancelar')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
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
            //'create' => Pages\CreateIntencaoDoacao::route('/create'),
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