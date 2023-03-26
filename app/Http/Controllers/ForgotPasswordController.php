<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Notifications\ResetPasswordOtpNotification;
use Otp;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }

        return [
            'email' => [trans($status)],
        ];
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed',],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }

    public function forgotPasswordOtp(Request $request) {
        $input = $request->only('email');
        $user = User::where('email', $input)->first();
        if($user == null) {
            return response()->json(["message" => "There is no registered user with this email."], 422);
        }
        $user->notify(new ResetPasswordOtpNotification());
        return response()->json(["message" => "Requested successfully. Check your email for the verification code."], 200);
    }

    public function __construct()
    {
        $this->otp = new Otp;
    }
    public function resetPasswordOtp(Request $request)
    {
        $otp2 = $this->otp->validate($request->email, $request->otp);
        if(! $otp2->status) {
            return response()->json(['error' => $otp2], 201);
        }
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
        $user->tokens()->delete();
        $success['success'] = true;
        return response()->json(["message" => "Password reset successfully"], 200);

    }
}
