<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Document;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentPreview extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.document-preview';

    public Document $document;

    public function mount(Document $document): void
    {
        $this->document = $document;
    }

    public function getFileUrl(): string
    {
        return Storage::disk('tenant_documents')->url($this->document->file_path);
    }

    public function download(): StreamedResponse
    {
        return Storage::disk('tenant_documents')->download($this->document->file_path);
    }
}
