<?php

namespace Tests\Unit\Jobs\Files;

use App\Jobs\Files\OptimizeImage;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Tests\TestCase;

class OptimizeImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function resize_image_into_a_proper_width_or_height()
    {
        Storage::fake(config('filesystem.default'));

        Storage::makeDirectory('files');
        copy(public_path('screenshots/01-monthly-report-for-public.jpg'), Storage::path('files/landscape_image.jpg'));

        $imageFile = Image::read(Storage::path('files/landscape_image.jpg'));
        $this->assertEquals($imageFile->width(), 1000);
        $this->assertEquals($imageFile->height(), 1145);

        Storage::assertExists('files/landscape_image.jpg');
        $file = File::create([
            'file_path' => 'files/landscape_image.jpg',
            'type_code' => 'raw_image',
        ]);

        dispatch(new OptimizeImage($file));

        $imageFile = Image::read(Storage::path('files/landscape_image.jpg'));
        $this->assertEquals($imageFile->width(), 873);
        $this->assertEquals($imageFile->height(), 1000);

        Storage::assertExists('files/landscape_image.jpg');
        $this->seeInDatabase('files', [
            'id' => $file->id,
            'type_code' => 'image',
        ]);
    }

    /** @test */
    public function resize_image_skip_non_raw_images()
    {
        $file = File::create([
            'file_path' => 'files/landscape_image.jpg',
            'type_code' => 'other_type_code',
        ]);

        dispatch(new OptimizeImage($file));

        $this->seeInDatabase('files', [
            'id' => $file->id,
            'type_code' => 'other_type_code',
        ]);
    }
}
