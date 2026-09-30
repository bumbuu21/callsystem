@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 430px; margin: 72px auto;">
        <h1>Системд нэвтрэх</h1>
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <p><label>И-мэйл эсвэл нэвтрэх нэр<br><input name="login" value="{{ old('login', $rememberedLogin ?? '') }}" autocomplete="username" required autofocus></label></p>
            <p><label>Нууц үг<br><input type="password" name="password" autocomplete="current-password" required></label></p>
            <p><label><input type="checkbox" name="remember" value="1" @checked(old('remember') || $rememberedLogin)> Намайг сана</label><br><small>Нэвтрэх нэр болон нэвтрэх төлөвийг санана. Нууц үгийг аюулгүй байдлын үүднээс систем хадгалахгүй.</small></p>
            @error('login') <p style="color:#b42318">{{ $message }}</p> @enderror
            <button type="submit">Нэвтрэх</button>
        </form>
        <p>Шинэ хэрэглэгч үү? <a href="{{ route('register') }}">Бүртгүүлэх</a></p>
    </div>
@endsection
