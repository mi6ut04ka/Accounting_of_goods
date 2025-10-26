<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmailController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->route('id'));
        if ($user->hasVerifiedEmail()) {
            return redirect(asset(config('app.frontend_url'). '?email_verified_status=already_verified'));
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        if (!Auth::check()) {
            Auth::login($user);
        }

        return redirect(asset(config('app.frontend_url'). '?email_verified_status=success'));
    }

    public function verificationNotification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email уже подтверждён'], 200);
        }

        event(new Registered($request->user()));

        return response()->json(['message' => 'Ссылка для подтверждения отправлена'], 200);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);
        $user->update(['email' => $validated['email'], 'email_verified_at' => null]);

        $user->sendEmailVerificationNotification();

        return response()->json(['success' => 'Email изменен, ссылка для подверждения отправлена']);
    }
}
