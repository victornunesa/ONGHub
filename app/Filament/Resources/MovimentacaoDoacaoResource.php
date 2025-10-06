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
use Filament\Forms\Components\TextInput;

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
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('descricao')
                    ->label('Item'),
                    //->searchable(),

                TextColumn::make('quantidade')
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->unidade),

                TextColumn::make('data_validade')
                    ->label('Validade')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($state) => $state && now()->gt($state) ? 'danger' : 'success')
                    ->description(fn ($record) => 
                        !$record->data_validade ? '' : (
                            ($dias = intval(now()->diffInDays($record->data_validade, false))) < 0 
                                ? 'VENCIDO' 
                                : ($dias == 0 
                                    ? 'Vence hoje' 
                                    : ($dias <= 7 
                                        ? 'Vence em ' . $dias . ' dia' . ($dias > 1 ? 's' : '') 
                                        : ''
                                    )
                                )
                        )
                    ),

                TextColumn::make('status')
                    ->badge()
                    ->label('Operação')
                    ->color(fn (string $state): string => match ($state) {
                        'Entrada' => 'success',
                        'Saída' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('nome_doador')
                    ->label('Nome Beneficiário')
                    ->formatStateUsing(fn ($state, $record) => 
                        $record->status === 'Entrada' ? null :  
                        ($record->status === 'Saída' ? $state : $record->nome_doador)
                    )
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Entrada' => 'Entradas',
                        'Saída'   => 'Saídas',
                    ]),
                
                Filter::make('descricao')
                    ->label('Item')
                    ->form([
                        TextInput::make('value')
                            ->label('Item')
                            ->placeholder('Buscar por item...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['value']) {
                            $query->where('descricao', 'like', '%' . $data['value'] . '%');
                        }
                        return $query;
                    }),

                Filter::make('data_validade')
                    ->label('Validade')
                    ->form([
                        Forms\Components\DatePicker::make('validade_inicio'),
                        Forms\Components\DatePicker::make('validade_fim'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['validade_inicio'])) {
                            $query->whereDate('data_validade', '>=', Carbon::parse($data['validade_inicio'])->format('Y-m-d'));
                        }
                        if (!empty($data['validade_fim'])) {
                            $query->whereDate('data_validade', '<=', Carbon::parse($data['validade_fim'])->format('Y-m-d'));
                        }
                        return $query;
                    }),

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
                                Column::make('data_validade')->heading('Data de Validade'),
                                Column::make('status')->heading('Operação'),
                                Column::make('nome_doador')
                                    ->heading('Nome Beneficiário')
                                    ->formatStateUsing(fn ($record) => 
                                        $record->status === 'Saída' ? $record->nome_doador : ($record->status === 'Entrada' ? $record->nome_doador : null)
                                    ),
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

                                // Filtro por validade
                                if (!empty($filters['data_validade']['validade_inicio'])) {
                                    $query->whereDate('data_validade', '>=', Carbon::parse($filters['data_validade']['validade_inicio'])->format('Y-m-d'));
                                }

                                if (!empty($filters['data_validade']['validade_fim'])) {
                                    $query->whereDate('data_validade', '<=', Carbon::parse($filters['data_validade']['validade_fim'])->format('Y-m-d'));
                                }

                                // Filtro por período
                                if (!empty($filters['periodo']['data_inicio'])) {
                                    $query->whereDate('data_doacao', '>=', Carbon::parse($filters['periodo']['data_inicio'])->format('Y-m-d'));
                                }

                                if (!empty($filters['periodo']['data_fim'])) {
                                    $query->whereDate('data_doacao', '<=', Carbon::parse($filters['periodo']['data_fim'])->format('Y-m-d'));
                                }

                                if (!empty($filters['descricao']['value'])) {
                                    $query->where('descricao', 'like', '%' . $filters['descricao']['value'] . '%');
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
            ->orderByRaw('CASE WHEN data_validade < NOW() THEN 0 ELSE 1 END')
            ->orderBy('data_validade', 'asc')
            ->orderBy('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovimentacaoDoacaos::route('/'),
        ];
    }
}