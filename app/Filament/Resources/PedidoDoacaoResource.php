<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidoDoacaoResource\Pages;
use App\Filament\Resources\PedidoDoacaoResource\RelationManagers;
use App\Models\Estoque;
use App\Models\PedidoDoacao;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class PedidoDoacaoResource extends Resource
{
    protected static ?string $model = PedidoDoacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome_solicitante')->searchable(),
                TextColumn::make('quantidade'),
                TextColumn::make('status')
                ->color(fn (string $state): string => match ($state) {
                    'Doação em aberto' => 'warning',
                    'Doação em parte' => 'gray',
                    'Doação completa' => 'success'
                })
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('verDescricao')
                ->label('Visualizar')
                ->icon('heroicon-m-eye')
                ->modalHeading('Descrição completa')
                ->modalContent(fn ($record) => new HtmlString(nl2br(e($record->descricao))))
                ->modalSubmitAction(false)
                ->modalCancelAction(false),

                Action::make('doar')
                ->label('Doar')
                ->icon('heroicon-m-hand-thumb-up')
                ->visible(fn ($record) => $record->status !== 'Doação completa')
                ->fillForm(fn ($record) => [
                    'quantidade_solicitada_pedido' => $record->quantidade,
                ])
                ->record(fn ($record) => $record)
                ->form([
                    Select::make('estoque_id')
                        ->label('Item do estoque')
                        ->options(fn () =>
                            Estoque::all()->pluck('nome_item', 'id')
                        )
                        ->required()
                        ->reactive(),

                    TextInput::make('quantidade_solicitada_pedido')
                        ->default(fn ($record) => $record->quantidade)
                        ->hidden()
                        ->dehydrated() // necessário para enviar o valor junto
                        ->required(),

                    TextInput::make('quantidade')
                        ->numeric()
                        ->required()
                        ->label('Quantidade a doar')
                        ->minValue(1)
                        ->rule(function (Get $get) {
                            $estoqueId = $get('estoque_id');
                            $estoque = Estoque::find($estoqueId);
                            $quantidadeEstoque = $estoque?->estoque_real ?? 0;

                            $quantidadeSolicitada = $get('quantidade_solicitada_pedido') ?? 0;

                            $limite = min($quantidadeEstoque, $quantidadeSolicitada);

                            return 'max:' . $limite;
                        })
                        ->helperText(function (Get $get) {
                            $estoqueId = $get('estoque_id');
                            $estoque = Estoque::find($estoqueId);
                            $estoqueQtd = $estoque?->estoque_real ?? 0;

                            $pedidoQtd = $get('quantidade_solicitada_pedido') ?? 0;

                            return 'Máximo permitido: ' . min($estoqueQtd, $pedidoQtd)
                                . ' (Pedido: ' . $pedidoQtd
                                . ', Estoque: ' . $estoqueQtd . ')';
                        }),
                ])
                ->action(function ($record, array $data) {
                    $estoque = Estoque::find($data['estoque_id']);

                    // Atualiza status do pedido
                    if ($data['quantidade'] == $record->quantidade) {
                        $record->update(['status' => 'Doação completa']);
                    } else {
                        $record->update(['status' => 'Doação em parte']);
                    }

                    $estoque->quantidade_solicitada += $data['quantidade'];
                    $estoque->save();

                    Notification::make()
                        ->title('Doação registrada com sucesso')
                        ->success()
                        ->send();

                })
                ->requiresConfirmation()
                ->modalHeading('Confirmar Doação'),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPedidoDoacaos::route('/'),
            'create' => Pages\CreatePedidoDoacao::route('/create'),
            'edit' => Pages\EditPedidoDoacao::route('/{record}/edit'),
        ];
    }
}
