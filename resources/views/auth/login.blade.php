@extends('layouts.guest')
@section('title', 'Вход')

@section('content')
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Вход</h5>
                <form method="POST" action="{{route('authenticate')}}">
                    @csrf
                    <x-input label="Email" name="email" type="email"/>
                    <x-input label="Пароль" name="password" type="password"/>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Запомнить меня</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Войти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
