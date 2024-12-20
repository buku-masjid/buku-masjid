<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = ['fileable_id', 'fileable_type', 'type_code', 'file_path', 'title', 'description'];

    public function delete()
    {
        Storage::delete($this->file_path);

        return parent::delete();
    }
}
