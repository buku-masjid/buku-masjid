<?php

namespace App\Jobs\Files;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class OptimizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function handle(): void
    {
        $file = $this->file;
        if ($file->type_code != 'raw_image') {
            return;
        }

        $image = Image::read(Storage::path($file->file_path));
        $image->scale(1000, 1000);
        $image->save();

        $file->type_code = 'image';
        $file->save();
    }
}
