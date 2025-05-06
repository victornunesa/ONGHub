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
                    'quantidade_solicitada' => $record->quantidade,
                ])
                ->record(fn ($record) => $record)
                ->form([
                    Select::make('estoque_id')
                        ->label('Item do estoque')
                        ->options(fn () =>
                            Estoque::all()->pluck('nome_item', 'id')
                        )
                        ->required(),

                    TextInput::make('quantidade_solicitada')
                        ->label('Quantidade Solicitada')
                        ->disabled() // apenas visual
                        ->dehydrated(false), // não envia para o backend de novo

                    TextInput::make('quantidade')
                        ->label('Quantidade a doar')
                        ->numeric()
                        ->required()
                        ->minValue(1),
                ])
                ->action(function ($record, array $data) {
                    $estoque = Estoque::find($data['estoque_id']);

                    if (!$estoque) {
                        Notification::make()
                            ->title('Item de estoque inválido')
                            ->danger()
                            ->send();
                        return;
                    }

                    if ($data['quantidade'] > $estoque->quantidade) {
                        Notification::make()
                            ->title('Estoque insuficiente')
                            ->body('Disponível: ' . $estoque->quantidade)
                            ->danger()
                            ->send();
                        return;
                    }

                    if ($data['quantidade'] > $record->quantidade) {
                        Notification::make()
                            ->title('Quantidade excede o pedido')
                            ->body('O pedido exige no máximo ' . $record->quantidade)
                            ->danger()
                            ->send();
                        return;
                    }

                    // Atualiza status do pedido
                    if ($data['quantidade'] == $record->quantidade) {
                        $record->update(['status' => 'Doação completa']);
                    } else {
                        $record->update(['status' => 'Doação em parte']);
                    }

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
