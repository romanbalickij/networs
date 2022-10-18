<?php

namespace Tests\Unit;


use App\Http\Resources\User\UserPostCollection;
use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    public $email;

    public $password;

    public function setUp():void {

        parent::setUp();

        $this->email = 'user@user.com';

        $this->password = 'user@user.com';

    }

    /**
     * Login as default API user and get token back.
     *
     * @return void
     */
    public function testLogin()
    {
        $this->withExceptionHandling();

        $baseUrl = env('APP_URL') . '/api/login';

        $email = $this->email;

        $password = $this->password;

         $this->json('POST', $baseUrl, [
            'email' => $email,
            'password' => $password
        ])    ->assertStatus(200);

    }

    public function testLogout()
    {
        $user = User::where('email', $this->email)->first();

        $token = JWTAuth::fromUser($user);

        $baseUrl = env('APP_URL') . '/api/logout?token='.$token;

        $response = $this->json('POST', $baseUrl, []);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'Successfully logged out'
            ]);
    }

    public function testGetUser()
    {
        $user = User::where('email', $this->email)->first();

        $token = JWTAuth::fromUser($user);

        $baseUrl = env('APP_URL') . "/api/users/$user->id?token=" . $token;

        $response = $this->json('GET', $baseUrl . '/', []);

        $response->assertStatus(200);
    }

    public function testGetUserPost()
    {
        $user = User::where('email', $this->email)->first();

        $token = JWTAuth::fromUser($user);

        $baseUrl = env('APP_URL') . "/api/users/$user->id/posts?token=" . $token;

        $response = $this->json('GET', $baseUrl . '/', []);

        $response->assertStatus(200);

        $response->assertResource(  (new UserPostCollection($user
            ->posts()
            ->visibleUntil()
            ->orderBy('id', 'desc')
            ->with([
                'reactions',
                'others.entity.payments',
                'media.entity.payments',
                'user',
                'bookmarks',
                'allComments',
                'media.bookmarks',
                'others.bookmarks'
            ])->cursorPaginate(20)
        )
        )->except('creator'));


    }
}
//vendor/bin/phpunit
