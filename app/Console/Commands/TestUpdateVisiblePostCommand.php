<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class TestUpdateVisiblePostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:post';

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
        $i = 0;
        Post::query()
            ->chunkById(50, function($posts, &$i) {

                foreach ($posts as $post) {

                    $post->update([
                        'visible_until' => now()->addMonths(3)
                    ]);
                    $i++;

                    echo "---- Update post visible_until $i \n";
                }

            });

            $this->info('done');
    }
}
