<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Batch;

class ProdutsProduproducer extends BaseWidget
{

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $modelLabel = 'Lote';

    protected static ?string $heading = 'Ultimos Lotes';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('access_panel');
    }

    protected function getTableQuery(): Builder
    {
        if(auth()->user()->hasRole(['Admin', 'Manager', 'Lecturer'])){
            return  Batch::query()->latest();
        }else{
            return  Batch::query()->where('producer_id', auth()->user()->id)->latest();
        }
        
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
            ->label('Lote')
            ->sortable(),
        Tables\Columns\TextColumn::make('products.name')
            ->label('Produto'),
        Tables\Columns\TextColumn::make('producers.name')
            ->label('Produtor'),
        Tables\Columns\TextColumn::make('amount')
            ->label('Quantidade'),
        Tables\Columns\TextColumn::make('discard') 
            ->label('Descarte'),    
        Tables\Columns\TextColumn::make('defect') 
            ->label('Defeito'),   
        Tables\Columns\TextColumn::make('approved') 
            ->label('Aprovado'),   
        Tables\Columns\TextColumn::make('date')
            ->label('Data')
            ->date('d/m/Y'),
        Tables\Columns\TextColumn::make('lecturers.name')
            ->label('Conferente'),
        Tables\Columns\BadgeColumn::make('status')
            ->enum([
                1 => 'Aberto',
                2 => 'Conferindo',
                3 => 'Fechado',
            ])
            ->colors([
                'primary' => 2,
                'success' => 1,
                'danger' => 3,
            ])
            ->icons([
                'heroicon-o-clipboard-check' => 2,
                'heroicon-o-lock-open' => 1,
                'heroicon-o-lock-closed' => 3,
            ])
            ->sortable(),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole(['Admin', 'Manager'])
        ? parent::getEloquentQuery()
        : parent::getEloquentQuery()->whereHas(
            'producers',
            fn(Builder $query) => $query->where('id', auth()->user()->id)
        );
    }
}
