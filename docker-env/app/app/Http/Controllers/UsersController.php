<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * パスワードリセット
     */

    // パスワード再設定用のメール送信フォーム
    public function requestResetPassword()
    {
        return view('users.reset_input_mail');
    }

    //  メール送信 
    public function sendResetPasswordMail(ResetInputMailRequest $request)
    {
        return redirect()->route('reset.send.complete');
    }

    // メール送信完了
    public function sendCompleteResetPasswordMail()
    {
        return view('users.reset_input_mail_complete');
    }

    // パスワード再設定
    public function resetPassword(Request $request)
    {
        return view('users.reset_input_password');
    }

    // パスワード更新
    public function updatePassword(ResetPasswordRequest $request)
    {
        return view('users.reset_input_password_complete');
    }
}
