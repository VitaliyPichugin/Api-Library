<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class AuthTest extends TestCase
{

    const URI = 'api/auth';

    /**
     * Test login.
     *
     * @return void
     */
    public function testLogin()
    {
        $email = Config::get('api.apiEmail');
        $password = Config::get('api.apiPassword');

        $response = $this->json('POST', self::URI . '/login', [
            'email' => $email,
            'password' => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                                      'access_token',
                                      'token_type',
                                      'expires_in'
                                  ]);
    }

    /**
     * Test logout.
     *
     * @return void
     */
    public function testLogout()
    {
        $user = User::where('email', Config::get('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->json('POST', self::URI . "/logout?token=$token");

        $response
            ->assertStatus(200)
            ->assertExactJson([
                                  'message' => 'Successfully logged out'
                              ]);
    }

    /**
     * Test token refresh.
     *
     * @return void
     */
    public function testRefresh()
    {
        $user = User::where('email', Config::get('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->json('POST', self::URI . "/refresh?token=$token");

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                                      'access_token',
                                      'token_type',
                                      'expires_in'
                                  ]);
    }

    /**
     * Test signup
     *
     * @return void
     */
    public function testSignup()
    {
        $user['name'] = 'Test User';
        $user['email'] = Str::random(5) . '@mail.com';
        $user['password'] = 'password';
        $user['password_confirmation'] = 'password';

        $response = $this->json('POST', self::URI . '/signup', $user);

        $this->assertDatabaseHas('users', [
            'id' => $response['user_id'],
            'name' => $response['name'],
            'email' => $response['email'],
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                                      'access_token',
                                      'token_type',
                                      'expires_in'
                                  ]);
    }
}
