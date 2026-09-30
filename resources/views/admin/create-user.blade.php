@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:680px; margin:auto">
        <h1>Хэрэглэгч нэмэх</h1>
        <p>Инженерийн эрх сонговол инженерийн бүртгэл автоматаар үүснэ.</p>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <p><label>Нэр<input name="name" value="{{ old('name') }}" required></label>@error('name') <span style="color:#b42318">{{ $message }}</span> @enderror</p>
            <p><label>И-мэйл<input type="email" name="email" value="{{ old('email') }}" required></label>@error('email') <span style="color:#b42318">{{ $message }}</span> @enderror</p>
            <p><label>Нэвтрэх нэр<input name="username" value="{{ old('username') }}" required></label>@error('username') <span style="color:#b42318">{{ $message }}</span> @enderror</p>
            <p><label>Утас<input name="phone" value="{{ old('phone') }}"></label></p>
            <p><label>Эрх<select name="role" required><option value="customer">Харилцагч</option><option value="operator">Оператор</option><option value="agent">Инженер</option><option value="admin">Админ</option></select></label></p>
            <p><label>Албан тушаал (инженер бол)<input name="title" value="{{ old('title') }}"></label></p>
            <p><label>Нууц үг<input type="password" name="password" required></label>@error('password') <span style="color:#b42318">{{ $message }}</span> @enderror</p>
            <p><label>Нууц үг давтах<input type="password" name="password_confirmation" required></label></p>
            <button type="submit">Хадгалах</button> <a href="{{ route('admin.users') }}">Болих</a>
        </form>
    </div>
@endsection
