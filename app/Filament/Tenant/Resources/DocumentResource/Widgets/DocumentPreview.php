<?php

namespace App\Filament\Tenant\Resources\DocumentResource\Widgets;

use App\Models\Document;
use Filament\Widgets\Widget;

class DocumentPreview extends Widget
{
    protected static string $view = 'filament.resources.document-resource.widgets.document-preview';

    public Document $document;


    public function getPreviewUrl(): string
    {
        if (str_starts_with($this->document->file_type, 'image/')) {
            return asset("storage/" . $this->document->path);
        }

        if ($this->document->file_type === 'application/pdf') {
            return route('filament.pages.document-preview', ['document' => $this->document->id]);
        }

        return '';
    }

    public function canPreview(): bool
    {
        return in_array($this->document->file_type, [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
        ]);
    }
}
