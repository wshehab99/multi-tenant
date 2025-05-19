<div class="space-y-4">
    @if(str_starts_with($document->mime_type, 'image/'))
        <img src="{{ $document->preview_url }}" class="max-w-full max-h-[70vh] mx-auto rounded-lg">
    @elseif($document->mime_type === 'application/pdf')
        <iframe src="{{ $document->preview_url }}" class="w-full h-[70vh] border rounded-lg"></iframe>
    @else
        <div class="p-4 bg-gray-50 rounded-lg text-center">
            <x-heroicon-o-document class="h-12 w-12 text-gray-400 mx-auto" />
            <p class="mt-2 text-gray-500">Preview not available</p>
        </div>
    @endif

    <div class="text-sm text-gray-500 space-y-1">
        <p>Uploaded: {{ $document->created_at->diffForHumans() }}</p>
        <p>Size: {{ round($document->size / 1024, 1) }} KB</p>
        <p>Type: {{ $document->mime_type }}</p>
    </div>
</div>