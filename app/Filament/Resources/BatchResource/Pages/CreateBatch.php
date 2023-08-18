<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBatch extends CreateRecord
{
    protected static string $resource = BatchResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['producer_id'] = auth()->id();
        $data['date'] = now();
        $data['status'] = 1;
        $data['discard'] = 0;
        $data['defect'] = 0;
        $data['approved'] = 0;
 
        return $data;
    }


}
