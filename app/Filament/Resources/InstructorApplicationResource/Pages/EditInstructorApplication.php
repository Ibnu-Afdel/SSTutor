<?php

namespace App\Filament\Resources\InstructorApplicationResource\Pages;

use App\Filament\Resources\InstructorApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstructorApplication extends EditRecord
{
    protected static string $resource = InstructorApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
