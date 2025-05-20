<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IntencaoDoacaoResource\Pages;
use App\Filament\Resources\IntencaoDoacaoResource\RelationManagers;
use App\Models\Estoque;
use App\Models\IntencaoDoacao;
use App\Models\Ong;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class IntencaoDoacaoResource extends Resource
{
    protected static ?string $model = IntencaoDoacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

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
                        $ongId = auth()->user()->ong->id;
                        
                        // Verifica se o item já existe no estoque
                        $itemEstoque = Estoque::firstOrNew([
                            'ong_id' => $ongId,
                            'nome_item' => $record->descricao,
                            'unidade' => $data['unidade_recebida']
                        ]);
                        
                        // Atualiza a quantidade
                        $itemEstoque->quantidade += $data['quantidade_recebida'];
                        $itemEstoque->quantidade_solicitada = 0;
                        $itemEstoque->data_atualizacao = now();
                        $itemEstoque->save();
                        
                        // Atualiza o status
                        $record->update([
                            'status' => 'Recebida',
                            'quantidade' => $data['quantidade_recebida'],
                            'unidade' => $data['unidade_recebida']
                        ]);
                        
                        Notification::make()
                            ->title('Doação registrada com sucesso!')
                            ->success()
                            ->send();
                    })
                    ->modalWidth('md')
                    ->modalHeading(fn ($record) => 'Receber: ' . $record->descricao)
                    ->modalDescription('Confirme os detalhes da doação física recebida')
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
            'create' => Pages\CreateIntencaoDoacao::route('/create'),
            'edit' => Pages\EditIntencaoDoacao::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Verifica se o usuário está autenticado e tem uma ONG associada
        if (auth()->check() && auth()->user()->ong) {
            $query->where('ong_desejada', auth()->user()->ong->id);
        } else {
            // Se não houver ONG associada, não mostra nada
            $query->whereNull('id');
        }
        
        return $query;
    }
}