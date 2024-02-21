<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Notifications\EmailVerifiedNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
  public function verify(EmailVerificationRequest $request)
  {
    $user = User::find($request->input('id'));
    if (!$user) {
      return response()->json(['success' => false, 'errors' => [__('auth.verification_invalid')]]);
    }
    if ($user->hasVerifiedEmail()) {
      return response()->json(['success' => false, 'errors' => [__('auth.email_verified')]]);
    }

    $user->markEmailAsVerified();
    $user->sendVerifiedEmailNotification();

    return response()->json(['success' => true, 'message' => __('auth.verification_completed')]);

  }

  public function me(Request $request)
  {
    $user = Auth::user();
    if (!$user) {
      return response()->json(['success' => false, 'errors' => [__('auth.user_not_found')]]);
    }
    $admin = $request->input('admin');
    if ($admin && !$user->hasRole('admin')) {
      return response()->json(['success' => false, 'errors' => [__('auth.not_admin')]]);
    }
    if ($admin && !$user->hasVerifiedEmail()) {
      return response()->json(['success' => false, 'errors' => [__('auth.email_not_admin')]]);
    }

    return response()->json([
      'success' => true,
      'data' => [
        'user' => $user,
      ]
    ]);
  }

  public function login(LoginRequest $request)
  {
    $user = User::where('email', $request->input('email'))->first();

    if (!$user || !Hash::check($request->input('password'), $user->password)) {
      return response()->json(['success' => false, 'errors' => [__('auth.failed')]]);
    }
    $admin = $request->input('admin');
    if ($admin && !$user->hasRole('admin')) {
      return response()->json(['success' => false, 'errors' => [__('auth.not_admin')]]);
    }
    if ($admin && !$user->hasVerifiedEmail()) {
      return response()->json(['success' => false, 'errors' => [__('auth.email_not_admin')]]);
    }
    $token = $user->createToken('authToken', ['expires_in' => 60 * 24 * 30])->plainTextToken;

    return response()->json(['success' => true, 'message' => __('auth.login_success'), 'data' => ['token' => $token]]);
  }

  public function register(RegisterRequest $request)
  {
    $user = User::where('email', $request->email)->first();
    if ($user) {
      return response()->json(['success' => false, 'errors' => [__('auth.email_already_exists')]]);
    }
    $user = User::create([
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);
    $user->assignRole('user');
    $user->sendEmailVerificationNotification();
    return response()->json(['success' => true, 'message' => __('auth.register_success')]);
  }

  public function logout()
  {
    $user = Auth::user();
    $user->tokens()->delete();
    return response()->json(['success' => true, 'message' => __('auth.logout_success')]);
  }

  public function requestPasswordReset(Request $request)
  {
    $email = $request->email;
    $status = Password::sendResetLink(['email' => $email]);
    if ($status === Password::RESET_LINK_SENT) {
      return response()->json(['success' => true, 'message' => __('auth.password_reset_link_sent')]);
    } elseif ($status === Password::INVALID_USER) {
      return response()->json(['success' => false, 'errors' => [__('users.not_found')]]);
    } elseif ($status === Password::INVALID_TOKEN) {
      return response()->json(['success' => false, 'errors' => [__('auth.invalid_token')]]);
    } elseif ($status === Password::RESET_THROTTLED) {
      return response()->json(['success' => false, 'errors' => [__('auth.reset_throttled')]]);
    }
    return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
  }

  public function resetPassword(Request $request)
  {
    $status = Password::reset(
      $request->only('email', 'password', 'password_confirmation', 'token'),
      function ($user, $password) {
        $user->password = Hash::make($password);
        $user->save();
      }
    );
    if ($status === Password::PASSWORD_RESET) {
      return response()->json(['success' => true, 'message' => __('auth.password_reset_success')]);
    } elseif ($status === Password::INVALID_USER) {
      return response()->json(['success' => false, 'errors' => [__('users.not_found')]]);
    } elseif ($status === Password::INVALID_TOKEN) {
      return response()->json(['success' => false, 'errors' => [__('auth.invalid_token')]]);
    } elseif ($status === Password::RESET_THROTTLED) {
      return response()->json(['success' => false, 'errors' => [__('auth.reset_throttled')]]);
    }
    return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
  }
}
