@extends('layouts.app')

@section('content')
    <h1>Системийн лог</h1><p><a href="{{ route('admin.calls') }}">← Админы тайлан</a></p>
    <div class="card"><table><thead><tr><th>Огноо</th><th>Хэрэглэгч</th><th>Үйлдэл</th><th>IP</th></tr></thead><tbody>
    @forelse ($logs as $log)<tr><td>{{ $log->done_at->format('Y-m-d H:i') }}</td><td>{{ $log->user?->username ?? 'Систем' }}</td><td>{{ \App\Support\Labels::action($log->action) }}</td><td>{{ $log->ip_address }}</td></tr>@empty<tr><td colspan="4">Лог байхгүй.</td></tr>@endforelse
    </tbody></table><div style="margin-top:16px">{{ $logs->links() }}</div></div>
@endsection
