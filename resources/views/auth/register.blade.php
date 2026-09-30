@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 500px; margin: 48px auto;">
        <h1>Харилцагчийн бүртгэл</h1>
        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <p><label>Овог, нэр<br><input name="name" value="{{ old('name') }}" required></label> @error('name') {{ $message }} @enderror</p>
            <p><label>Утас<br><input name="phone" value="{{ old('phone') }}"></label></p>
            <p><label>И-мэйл<br><input type="email" name="email" value="{{ old('email') }}" required></label> @error('email') {{ $message }} @enderror</p>
            <p><label>Нэвтрэх нэр<br><input name="username" value="{{ old('username') }}" required></label> @error('username') {{ $message }} @enderror</p>
            <p><label>Аймаг/хот<br><select name="aimag_name"><option value="">Сонгох</option>@foreach(config('locations.aimags') as $location)<option @selected(old('aimag_name') === $location)>{{ $location }}</option>@endforeach</select></label></p>
            <p><label>Сум/дүүрэг<br><select name="soum_name"><option value="">Сонгох</option>@foreach(config('locations.soums') as $location)<option @selected(old('soum_name') === $location)>{{ $location }}</option>@endforeach</select></label></p>
            <p><label>Нууц үг<br><input type="password" name="password" required></label> @error('password') {{ $message }} @enderror</p>
            <p><label>Нууц үг давтах<br><input type="password" name="password_confirmation" required></label></p>
            <button type="submit">Бүртгүүлэх</button>
        </form>
        <p><a href="{{ route('login') }}">Нэвтрэх хуудас руу буцах</a></p>
    </div>
@endsection
