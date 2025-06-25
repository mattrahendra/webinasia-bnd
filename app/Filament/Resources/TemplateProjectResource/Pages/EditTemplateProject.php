<?php

namespace App\Filament\Resources\TemplateProjectResource\Pages;

use App\Filament\Resources\TemplateProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTemplateProject extends EditRecord
{
    protected static string $resource = TemplateProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Process ZIP file after record is updated (jika ada file baru)
        if ($this->record->wasChanged('project_file')) {
            TemplateProjectResource::processZipFile($this->record);
        }
    }
}
