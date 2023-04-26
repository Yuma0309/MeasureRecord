<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/titlesindex'); // タイトル一覧のページにGETアクセス

        $response->assertStatus(302); // アクセス時のステータスコードをチェック（認証できない）
    }
}
