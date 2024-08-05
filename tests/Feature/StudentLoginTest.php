<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentLoginTest extends TestCase
{
    /**
     * 学生登录测试
     */
    public function test_login(): void
    {
        $account = 'phpunit-test-1';
        $password = '123456';

        $response = $this->post('/api/login', ['type' => 'student','username' => $account, 'password' => $password]);

        $resJson =  $response->json();

        $this->assertTrue($resJson['code'] === 0);

    }
}
