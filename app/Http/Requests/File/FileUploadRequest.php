<?php

namespace App\Http\Requests\File;

use App\Enums\FileType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileUploadRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        //1 MB test 1024
        //10 mb test = 10240
        //20 mb test = 20480
        //prod 1 GB = 1048576
        return [
       //     'main'   => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf,mp4,webm,ogg,mov|max:1048576',
        //    'blur'   => 'file|mimes:png,jpg,jpeg',
         //   'poster' => 'file|mimes:png,jpg,jpeg',
        //    'type'   => 'required|string|'.Rule::in(FileType::MODEL_POST, FileType::MODEL_MESSAGE),


        ];
    }
}
