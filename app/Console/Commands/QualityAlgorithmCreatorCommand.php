<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class QualityAlgorithmCreatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quality:creator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Змінні

            user_verified Чи верифікований користувач

            email_verified Чи верифікований email

            profile_filled Чи заповнені 5 найголовніших полів (name, avatar, url, phone, description)

            quality = (A*user_verified  + B* email_verified + C*profile_filled)* SUM(posts.quality)';

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
        User::query()
            ->chunkById(100, function ($users) {

                $users->each(function ($user) {

                    [
                        'rand_coefficient_user_verified' => $A,
                        'rand_coefficient_email_verified' => $B,
                        'rand_coefficient_profile_filled' => $C,

                    ] = getJsonConfig('algorithm-sort-config');


                    $isEmailVerified = (int) $user->isEmailVerified();

                    $userVerified    = (int) $user->verified;

                    $profileFilled   = (int)($user->name and $user->avatar and $user->avatar and $user->phone and $user->description);

                    $postQuality     = $user->posts->sum('quality');

                    $quality         = ($A * $userVerified + $B * $isEmailVerified + $C + $profileFilled) * $postQuality;

                    $user->update([
                        'quality' => $quality
                    ]);

                });
            });
    }
}
