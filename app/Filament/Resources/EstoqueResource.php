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
    protected static ?string $navigationLabel = 'Estoque';
    protected static ?string $modelLabel = 'Estoque ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome_item')
                    ->label('Nome do item')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('quantidade')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->minValue(0.1),
                
                Select::make('unidade')
                    ->options([
                        'g' => 'Grama',
                        'kg' => 'Kilograma',
                        'ml' => 'Mililitros',
                        'l' => 'Litro',
                        'un' => 'Unidade'
                    ])
                    ->native(false)
                    ->required(),
                
                Forms\Components\DatePicker::make('data_validade')
                    ->label('Data de Validade')
                    ->required()
                    ->minDate(now()->addDay())
                    ->helperText('Informe a data de validade do item'),
                
                // Campos para o doador
                TextInput::make('nome_doador')
                    ->label('Nome do doador')
                    ->required()
                    ->maxLength(100),
                
                TextInput::make('email_doador')
                    ->label('Email do doador')
                    ->email()
                    ->maxLength(150),
                
                TextInput::make('telefone_doador')
                    ->label('Telefone do doador')
                    ->maxLength(15),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome_item')
                    ->label('Item')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('quantidade')
                    ->label('Quantidade')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->unidade),
                
                TextColumn::make('data_validade')
                    ->label('Validade')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($state) => now()->gt($state) ? 'danger' : 'success')
                    ->description(fn ($record) => 
                        ($dias = intval(now()->diffInDays($record->data_validade, false))) < 0 
                            ? 'VENCIDO' 
                            : ($dias == 0 
                                ? 'Vence hoje' 
                                : ($dias <= 7 
                                    ? 'Vence em ' . $dias . ' dia' . ($dias > 1 ? 's' : '') 
                                    : ''
                                )
                            )
                    ),
                
                TextColumn::make('unidade')
                    ->label('Unidade')
                    ->toggleable(isToggledHiddenByDefault: true), // Opcional: ocultar por padrão
                
                TextColumn::make('created_at')
                    ->label('Entrada no estoque')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('validade_proxima')
                    ->label('Vencimento próximo (7 dias)')
                    ->query(fn (Builder $query) => $query->whereBetween('data_validade', [now(), now()->addDays(7)])),
                
                Tables\Filters\Filter::make('vencidos')
                    ->label('Itens vencidos')
                    ->query(fn (Builder $query) => $query->where('data_validade', '<', now())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
            #->defaultSort('data_validade', 'asc'); // Ordenar por validade por padrão
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
        ->where('ong_id', auth()->user()->ong->id)
        ->orderBy('data_validade', 'asc') // Ordena por validade
        ->orderBy('created_at', 'desc'); // E depois por criação
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