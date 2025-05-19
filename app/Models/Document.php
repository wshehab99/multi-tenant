<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'size',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function getPreviewUrlAttribute()
    {
        return Storage::disk('tenant_documents')
            ->temporaryUrl($this->path, now()->addMinutes(30));
    }
}
