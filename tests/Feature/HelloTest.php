<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class HelloTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // use RefreshDatabase; // レコードのデータを全て消します（php artisan migrate:resetと同じだと思う）
    
    public function testHello()
    {
        // 一般的な値のテスト
        $this->assertTrue(true); // trueが真

        $arr = []; // 配列
        $this->assertEmpty($arr); // 空またはnull

        $msg = "Hello"; // テキスト
        $this->assertEquals('Hello', $msg); // 'Hello' = "Hello"

        $n = random_int(0, 100); // 数値
        $this->assertLessThan(100, $n); // 100 > 0〜99

        // 指定アドレスにアクセスするテスト
        $this->assertTrue(true); // trueが真

        $response = $this->get('/titlesindex'); // タイトル一覧のページにGETアクセス
        $response->assertStatus(302); // アクセス時のステータスコードをチェック（認証できない）

        $user = User::factory()->create(); // モデルの作成
        $response = $this->actingAs($user)->get('/titlesindex'); // タイトル一覧のページにGETアクセス
        $response->assertStatus(200); // アクセス時のステータスコードをチェック（認証できる）

        $response = $this->get('/no_route'); // ページのないアドレスにGETアクセス
        $response->assertStatus(404); // アクセス時のステータスコードをチェック（ページが見つからない）

        // データベースのテスト
        User::factory()->create([ // モデル（ダミーユーザーのレコード）の作成
            'name' => 'AAA',
            'email' => 'BBB@CCC.COM',
            'password' => 'ABCABC',
        ]);
        User::factory(10)->create(); // モデルの作成（10個）

        $this->assertDatabaseHas('users', [ // モデルにダミーユーザーのレコードが存在するか確認
            'name' => 'AAA',
            'email' => 'BBB@CCC.COM',
            'password' => 'ABCABC',
        ]);

    }
}
