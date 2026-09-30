@extends('layouts.app')
@section('content')
<div class="card" style="max-width:650px;margin:auto"><h1>Профайл</h1><form method="POST" action="{{ route('profile.update') }}">@csrf @method('PATCH')
<p><label>Нэр<input name="name" value="{{ old('name',$user->name) }}" required></label></p><p><label>Утас<input name="phone" value="{{ old('phone',$user->phone) }}"></label></p><p><label>Аймаг/хот<input name="aimag_name" value="{{ old('aimag_name',$user->aimag_name) }}"></label></p><p><label>Сум/дүүрэг<input name="soum_name" value="{{ old('soum_name',$user->soum_name) }}"></label></p><button>Хадгалах</button></form></div>
@endsection
