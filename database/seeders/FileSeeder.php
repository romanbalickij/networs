<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "==========> Generating Test file  images, video, other..... \n\n";

        Storage::disk('images')->put('avatar.png', file_get_contents('https://gravatar.com/avatar/1d6ce50213ed0e3c9d9258f5f43183af?s=400&d=robohash&r=x'));

        Storage::disk('images')->put('background.png', file_get_contents('https://i.ibb.co/xqH4t1x/background-image-2.png'));

        Storage::disk('video')->put('video.mp4', file_get_contents('https://res.cloudinary.com/demo/video/upload/dog.mp4'));

        Storage::disk('other')->put('file.txt', 'Contents');

        echo "==========> OK .... \n\n";
    }
}
