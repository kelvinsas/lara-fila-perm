<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Closure;
use Illuminate\Database\Eloquent\Model;

class ListBatches extends ListRecords
{
    protected static string $resource = BatchResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getDefaultTableSortColumn(): ?string
    {
        return 'id';
    }
 
    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Model $record): string => false;
    }
}
