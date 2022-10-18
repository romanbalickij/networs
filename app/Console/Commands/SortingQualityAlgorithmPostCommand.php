<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class SortingQualityAlgorithmPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sorting:quality';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =
        '
            post.interests_count

            post.reactions_count

            post.comments_count

            post.total_earned

            post.creator.quality

            post.clicthrough_ratio = post.clicks / post.views

            post.minutes_ago - скільки хвилин тому був опублікований пост

            quality_for_creator =  (M * interests + N * reactions + O * comments + P * total_earned) * clickthrough_ratio * (R ^ - minutes_ago)

            quality = quality_for_creator + Q * creator_quality * clickthrough_ratio * (R ^ - minutes_ago)

            від M до R - довільні параметри з конфігу, R = 1.0003 за замовчанням
        ';

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
            ->chunkById(100, function($posts) {

                $posts->each(function ($post) {


                    $interestingCount = $post->interested;

                    $reactionCount    = $post->reaction_count;

                    $commentCount     = $post->commentCount();

                    $qualityCreator   = $post->quality_for_creator;

                    $totalEarned      = $post->payments->sum('sum');

                    $clicthroughRatio = round($post->clickthroughs / $post->shows ?: 1, 2);

                    $minutesAgo  = $post->updated_at->minute;

                    [
                        'rand_coefficient_interest' => $M,
                        'rand_coefficient_max_post_age' => $R,
                        'rand_coefficient_reaction' => $N,
                        'rand_coefficient_comment' => $O,
                        'rand_coefficient_total_earned' => $P,
                        'rand_coefficient_quality_for_creator' => $Q
                    ] = getJsonConfig('algorithm-sort-config');

                    $qualityForCreator = ($M * $interestingCount + $N * $reactionCount + $O * $commentCount + $P * $totalEarned) * $clicthroughRatio  * ($minutesAgo - pow($R, 2));

                    $quality = $qualityForCreator + $Q * $qualityCreator * $clicthroughRatio * ($minutesAgo - pow($R, 2));

                    $post->update([
                        'quality_for_creator' => $qualityForCreator,
                        'quality'             => $quality
                    ]);
                });

            });
    }
}
