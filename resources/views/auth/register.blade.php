@extends('layouts.guest')

@section('css')
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mt-5">Регистрация</h2>
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Имя:</label>
                        <input type="text" class="form-control" name="name" placeholder="Введите ваше имя" required>
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Электронная почта:</label>
                        <input type="email" class="form-control" name="email" placeholder="Введите ваш email" required>
                        @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Пароль:</label>
                        <input type="password" class="form-control" name="password" placeholder="Введите ваш пароль" required>
                        @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Пароль:</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Повторно введите пароль" required>
                        @error('password_confirmation')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
@endsection
