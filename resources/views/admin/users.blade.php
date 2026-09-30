@extends('layouts.app')

@section('content')
    <h1>Хэрэглэгчийн удирдлага</h1>
    <p><a href="{{ route('admin.calls') }}">← Админы тайлан</a> · <a class="button" href="{{ route('admin.users.create') }}">+ Хэрэглэгч нэмэх</a></p>
    <div class="card"><table><thead><tr><th>Нэр</th><th>Нэвтрэх нэр</th><th>Эрх</th><th>Төлөв</th><th>Үйлдэл</th></tr></thead><tbody>
    @foreach ($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->username }}</td><td>{{ \App\Support\Labels::role($user->role) }}</td><td>{{ \App\Support\Labels::accountStatus($user->status) }}</td><td>@if ($user->id !== auth()->id())<form style="display:inline" method="POST" action="{{ route('admin.users.toggle', $user) }}">@csrf @method('PATCH')<button type="submit">{{ $user->status === 'active' ? 'Идэвхгүй болгох' : 'Идэвхжүүлэх' }}</button></form> <form style="display:inline" method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Энэ хэрэглэгчийг устгах уу?')">@csrf @method('DELETE')<button type="submit">Устгах</button></form>@endif</td></tr>@endforeach
    </tbody></table><div style="margin-top:16px">{{ $users->links() }}</div></div>
@endsection
