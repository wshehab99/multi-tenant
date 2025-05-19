<x-filament::page>
    <x-filament::card>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">{{ $document->title }}</h2>
            <x-filament::button icon="heroicon-o-arrow-down-tray" wire:click="download">
                Download
            </x-filament::button>
        </div>

        <div class="h-[calc(100vh-200px)]">
            <iframe src="{{ $fileUrl }}" class="w-full h-full border rounded-lg"></iframe>
        </div>
    </x-filament::card>
</x-filament::page>