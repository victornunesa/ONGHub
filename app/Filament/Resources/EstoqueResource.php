<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstoqueResource\Pages;
use App\Filament\Resources\EstoqueResource\RelationManagers;
use App\Models\Estoque;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstoqueResource extends Resource
{
    protected static ?string $model = Estoque::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome_item')->label('Nome do item')->required()->maxLength(255),
                TextInput::make('quantidade')->label('Quantidade')->required()->numeric(),
                Select::make('unidade')
                ->options([
                    'g' => 'Grama',
                    'kg' => 'Kilograma',
                    'ml' => 'Mililitros',
                    'l' => 'Litro'
                ])->native(false)->required(),

                // Campos para o doador
                TextInput::make('nome_doador')->label('Nome do doador')->required()->maxLength(100),
                TextInput::make('email_doador')->label('Email do doador')->email()->maxLength(150),
                TextInput::make('telefone_doador')->label('Telefone do doador')->maxLength(15),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome_item'),
                TextColumn::make('quantidade'),
                TextColumn::make('quantidade_solicitada')->label('Quantidade'),
                TextColumn::make('unidade')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('ong_id', auth()->user()->ong->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstoques::route('/'),
            'create' => Pages\CreateEstoque::route('/create'),
            'edit' => Pages\EditEstoque::route('/{record}/edit'),
        ];
    }
}
