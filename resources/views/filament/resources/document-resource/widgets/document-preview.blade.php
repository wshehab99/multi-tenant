<x-filament::card>
    {{-- <x-filament::card.heading>
        Document Preview
    </x-filament::card.heading>

    <x-filament::card.content> --}}
        @if(str_starts_with($document->file_type, 'image/'))
            <div class="p-4">
                <img src="{{ $previewUrl }}" alt="{{ $document->title }}" class="max-w-full max-h-96 mx-auto">
            </div>
        @elseif($document->file_type === 'application/pdf')
            <div class="h-96">
                <iframe src="{{ $previewUrl }}" class="w-full h-full border rounded-lg"></iframe>
            </div>
        @endif
        {{-- @else
        <p class="text-gray-500 px-4 py-2">
            Preview not available for this file type.
        </p>
        @endif --}}
        {{--
    </x-filament::card.content> --}}
</x-filament::card>