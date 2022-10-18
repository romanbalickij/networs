<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class CountMediaPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count:media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Post::query()
            ->chunkById(100, function ($posts) {

                $posts->each(function ($post) {

                    $post->update([
                        'media_count' => $post->media->count(),
                        'video_count' => $post->videos->count(),
                        'image_count' => $post->images->count(),
                    ]);

                });
            });
    }
}
