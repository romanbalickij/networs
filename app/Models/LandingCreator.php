<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class LandingCreator extends Model
{
    use HasFactory,
        UploadFileTrait;

    protected $fillable =[
      'user_id',
      'main',
      'me',
      'img'
    ];

    public function addImage($image) {

        if(!$image) {
            return;
        }

        $this->forceFill([
            'img' => $this->uploadOne($image, 'images')
        ])->save();
    }

    public function deleteLanding()
    {
        if (!$this->img) {
            return;
        }
        $this->deleteFile($this->img);

        $this->delete();
    }

    public function getPatchImageAttribute($value) {

        return fileUrl($this->img);
    }

}
