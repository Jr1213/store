<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $user;
    protected $header;
    public function setUp(): void
    {
        parent::setUp();
        $this->user     = User::factory()->create();
        $this->header   = ['Authorization' => 'Bearer ' . $this->user->createToken(env('token_name'))->plainTextToken];
    }
}
