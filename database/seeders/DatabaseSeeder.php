<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       // $this->clearFile('images');
       // $this->clearFile('other');
      //  $this->clearFile('video');

        $this->call([
                UserPostSeeder::class,


//            Use from dev
//                ServiceSeeder::class,
//                WebsiteSeeder::class,
//                SetPosterSeeder::class,
//                PaymentSeeder::class,
//                HistorySeeder::class,
//                InteractionSeeder::class,
//                PaymentMethodSeeder::class
            ]);
    }

    protected function clearFile($directory) {

        $files = Storage::allFiles($directory);

        Storage::delete($files);
    }
}
