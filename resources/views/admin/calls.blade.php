@extends('layouts.app')

@section('content')
    <h1>Админы тайлан ба шүүлтүүр</h1>
    <p><a class="button" href="{{ route('admin.report', request()->query()) }}">Дуудлагын тайлан</a> <a class="button" href="{{ route('admin.calls.pdf', request()->query()) }}">PDF татах</a> <a class="button" href="{{ route('admin.calls.excel', request()->query()) }}">Excel татах</a></p>
    <div class="grid" style="margin-bottom:20px">
        <div class="card">Нийт<div class="metric">{{ $total }}</div></div>
        <div class="card">Илгээгдсэн<div class="metric">{{ $submitted }}</div></div>
        <div class="card">Хүлээн авсан<div class="metric">{{ $accepted }}</div></div>
        <div class="card">Шийдвэрлэсэн<div class="metric">{{ $resolved }}</div></div>
    </div>
    <div class="card" style="margin-bottom:20px">
        <h2>Шүүлтүүр</h2>
        <form method="GET" action="{{ route('admin.calls') }}" class="grid">
            <label>Эхлэх огноо<input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"></label>
            <label>Дуусах огноо<input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}"></label>
            <label>Он<input type="number" name="year" value="{{ $filters['year'] ?? '' }}" placeholder="2026"></label>
            <label>Сар<input type="number" name="month" value="{{ $filters['month'] ?? '' }}" min="1" max="12"></label>
            <label>Төрөл<select name="call_type"><option value="">Бүгд</option>@foreach (['network' => 'Сүлжээ', 'program' => 'Програм', 'hardware' => 'Тоног төхөөрөмж', 'other' => 'Бусад'] as $value => $label)<option value="{{ $value }}" @selected(($filters['call_type'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></label>
            <label>Төлөв<select name="status"><option value="">Бүгд</option>@foreach (['submitted' => 'Илгээгдсэн', 'accepted' => 'Хүлээн авсан', 'resolved' => 'Шийдвэрлэсэн'] as $value => $label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></label>
            <label>Инженер<select name="agent_id"><option value="">Бүгд</option>@foreach ($agents as $agent)<option value="{{ $agent->id }}" @selected(($filters['agent_id'] ?? '') == $agent->id)>{{ $agent->name }}</option>@endforeach</select></label>
            <label>Хайх<input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Нэр, байршил, тайлбар"></label>
            <label>Аймаг/хот<input name="aimag_name" value="{{ $filters['aimag_name'] ?? '' }}"></label>
            <label>Сум/дүүрэг<input name="soum_name" value="{{ $filters['soum_name'] ?? '' }}"></label>
            <label>Газар/хэлтэс<input name="call_from" value="{{ $filters['call_from'] ?? '' }}"></label>
            <p><button type="submit">Шүүх</button> <a href="{{ route('admin.calls') }}">Цэвэрлэх</a></p>
        </form>
    </div>
    <div class="card">
        <h2>Төрлөөр</h2><p>Сүлжээ: {{ $byType['network'] ?? 0 }} · Програм: {{ $byType['program'] ?? 0 }} · Тоног төхөөрөмж: {{ $byType['hardware'] ?? 0 }} · Бусад: {{ $byType['other'] ?? 0 }}</p>
        <table><thead><tr><th>#</th><th>Илгээгч</th><th>Байршил</th><th>Төрөл</th><th>Инженер</th><th>Төлөв</th></tr></thead><tbody>
        @foreach ($calls as $call)<tr><td><a href="{{ route('calls.show', $call) }}">#{{ $call->id }}</a></td><td>{{ $call->caller_name }}</td><td>{{ $call->call_from }}</td><td>{{ \App\Support\Labels::type($call->call_type) }}</td><td>{{ $call->agent?->name ?? 'Хуваарилаагүй' }}</td><td><span class="badge badge-{{ $call->status }}">{{ \App\Support\Labels::status($call->status) }}</span></td></tr>@endforeach
        </tbody></table>
        <div style="margin-top:16px">{{ $calls->links() }}</div>
    </div>
@endsection
