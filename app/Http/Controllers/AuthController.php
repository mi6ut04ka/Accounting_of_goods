<?php

    namespace App\Http\Controllers;

    use App\Models\User;
    use http\Header;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Laravel\Sanctum\PersonalAccessToken;

    class AuthController extends Controller
    {
        public function login()
        {
            return view('auth.login');
        }

        public function authenticate(Request $request)
        {
            $validated = $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required'
            ]);

            if (auth()->attempt($validated, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('/')->with('success', 'Успешная авторизация');
            }
            return back()->withErrors([
                'email' => 'Неправильный логин или пароль.',
            ])->withInput($request->only('email'));

        }
    }
