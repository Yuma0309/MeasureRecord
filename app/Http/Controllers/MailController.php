<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator; // バリデーションを使えるようにする
use SendGrid; // SendGridを使えるようにする
use SendGrid\Mail\Mail; // SendGridのMailクラスを使えるようにする
use \Symfony\Component\HttpFoundation\Response;

class MailController extends Controller
{
    // メール画面表示
    public function index() {
        return view('mail');
    }

    // メール送信処理
    public function send(Request $request) {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'regex:/([a-z0-9.!#$%&\'*+\/=?^_{|}~-]+)@([a-z0-9-]+(?:\.[a-z0-9-]+)*)/i'],
            'subject' => 'required',
            'contents' => 'required',
        ]);

        // バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/mail')->withErrors($validator);
        }

        // メールの内容
        $email = new Mail(); // メールの内容のオブジェクトを作成
        $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')); // 第1引数：送信元のメールアドレス、第2引数：送信元のメールアドレスの代わりに表示される名前
        $email->setSubject($request->subject); // 件名
        $email->addTo($request->email); // 送信先のメールアドレス
        $email->addContent("text/plain", $request->contents); // 第1引数：メールの種類（テキストメール）、第2引数：メールの本文

        $sendgrid = new SendGrid(env('MAIL_PASSWORD')); // 「.env」に設置したAPIキーを指定してSendGridのオブジェクトを作成

        // メールの送信
        $response = $sendgrid->send($email);
        if ($response->statusCode() == Response::HTTP_ACCEPTED) { // 送信状態 == 送信完了（Response::HTTP_ACCEPTED：202）
            return view('mail', ['successMessage' => '送信できました！']);
        } else {
            return view('mail', ['errorMessage' => '送信失敗しました！']);
        }
    }
}
