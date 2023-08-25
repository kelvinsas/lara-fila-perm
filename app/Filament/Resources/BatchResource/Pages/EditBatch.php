<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatch extends EditRecord
{
    protected static string $resource = BatchResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['lecturer_id'] = auth()->id();

        $data['status'] =  $data['status'] == 3 ?  $data['status'] : 2;

        $data['approved'] = isset($data['new-approved']) ? $data['approved'] + $data['new-approved'] : $data['approved'];


        return $data;
    }

}
