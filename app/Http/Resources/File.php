<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class File extends JsonResource
{
    public function toArray($request)
    {
        $file = $this->resource;

        return [
            'id' => $file->id,
            'transaction_id' => $file->fileable_id,
            'type_code' => $file->type_code,
            'file_path' => $file->file_path,
            'url' => Storage::url($file->file_path),
            'title' => $file->title,
            'description' => $file->description,
            'created_at' => $file->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
