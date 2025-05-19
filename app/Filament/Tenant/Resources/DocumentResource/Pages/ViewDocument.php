<?php

namespace App\Filament\Tenant\Resources\DocumentResource\Pages;

use App\Filament\Tenant\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('download')
                ->label('Download')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn() => asset("storage/" . $this->record->path))
                ->openUrlInNewTab(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            DocumentResource\Widgets\DocumentPreview::make([
                'document' => $this->record,
            ]),
        ];
    }
}
