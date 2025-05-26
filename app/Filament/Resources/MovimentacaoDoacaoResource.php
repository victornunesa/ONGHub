<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimentacaoDoacaoResource\Pages;
use App\Models\Doacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Carbon\Carbon;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use Maatwebsite\Excel\Excel;

class MovimentacaoDoacaoResource extends Resource
{
    protected static ?string $model = Doacao::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationLabel = 'Movimentações';
    protected static ?string $modelLabel = 'Movimentações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Formulário básico (pode ser expandido)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data/Hora')
                    ->dateTime('d/m/Y H:i'),

                TextColumn::make('descricao')
                    ->label('Item')
                    ->searchable(),

                TextColumn::make('quantidade')
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->unidade),

                TextColumn::make('status')
                    ->badge()
                    ->label('Operação')
                    ->color(fn (string $state): string => match ($state) {
                        'Entrada' => 'success',
                        'Saída' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('data_doacao')
                    ->label('Data Doação')
                    ->date('d/m/Y'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Entrada' => 'Entradas',
                        'Saída'   => 'Saídas',
                    ]),

                Filter::make('periodo')
                    ->form([
                        Forms\Components\DatePicker::make('data_inicio'),
                        Forms\Components\DatePicker::make('data_fim'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['data_inicio'])) {
                            $query->whereDate('data_doacao', '>=', Carbon::parse($data['data_inicio'])->format('Y-m-d'));
                        }
                        if (!empty($data['data_fim'])) {
                            $query->whereDate('data_doacao', '<=', Carbon::parse($data['data_fim'])->format('Y-m-d'));
                        }
                        return $query;
                    }),
            ])
            ->headerActions([
                ExportAction::make('exportar')
                    ->label('Exportar CSV/XLSX')
                    ->exports([
                        ExcelExport::make()
                            ->withColumns([
                                Column::make('created_at')->heading('Data/Hora'),
                                Column::make('descricao')->heading('Item'),
                                Column::make('quantidade')
                                    ->heading('Quantidade')
                                    ->formatStateUsing(fn ($record) => $record->quantidade . ' ' . $record->unidade),
                                Column::make('status')->heading('Operação'),
                                Column::make('data_doacao')->heading('Data Doação'),
                            ])
                            ->askForFilename()
                            ->askForWriterType(
                                default: Excel::XLSX,
                                options: [
                                    Excel::CSV => 'CSV',
                                    Excel::XLSX => 'XLSX',
                                ]
                            )
                            ->modifyQueryUsing(function ($query, $livewire) {
                                // Filtra registros da ONG logada
                                $query->where(function ($q) {
                                    $q->where('ong_destino_id', auth()->user()->ong->id)
                                        ->orWhere('ong_origem_id', auth()->user()->ong->id);
                                });

                                // Captura os filtros aplicados na tabela
                                $filters = $livewire->tableFilters ?? [];

                                // Filtro por status
                                if (!empty($filters['status']['value'])) {
                                    $query->where('status', $filters['status']['value']);
                                }

                                // Filtro por período
                                if (!empty($filters['periodo']['data_inicio'])) {
                                    $query->whereDate('data_doacao', '>=', Carbon::parse($filters['periodo']['data_inicio'])->format('Y-m-d'));
                                }

                                if (!empty($filters['periodo']['data_fim'])) {
                                    $query->whereDate('data_doacao', '<=', Carbon::parse($filters['periodo']['data_fim'])->format('Y-m-d'));
                                }

                                return $query;
                            })
                    ]),
            ])
            ->bulkActions([
                // Se quiser exportar em massa
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function (Builder $query) {
                $query->where('ong_destino_id', auth()->user()->ong->id)
                    ->orWhere('ong_origem_id', auth()->user()->ong->id);
            })
            ->orderBy('created_at', 'desc');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovimentacaoDoacaos::route('/'),
        ];
    }
}
