@extends('layouts.app')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <div><h1 style="margin-bottom:4px">{{ auth()->user()->role === 'agent' ? 'Таны хариуцах дуудлагууд' : 'Дуудлагын жагсаалт' }}</h1><span>@if(auth()->user()->role === 'operator') Илгээгдсэн дуудлагад инженер сонгож “Хуваарилах” дарна. @elseif(auth()->user()->role === 'agent') Улаан мөр нь 2-оос олон хоног хүлээгдсэн дуудлага. @else Төлөв болон шийдлийн тайлбарыг “Дэлгэрэнгүй” дээрээс харна. @endif</span></div>
        @if (auth()->user()->role === 'customer') <a class="button" href="{{ route('calls.create') }}">+ Шинэ дуудлага</a> @endif
    </div>
    <div class="card">
        @if ($calls->count() === 0) <p>Одоогоор дуудлага алга.</p>
        @else
            <table><thead><tr><th>Харилцагч</th><th>Төрөл</th><th>Хаанаас</th><th>Төлөв</th><th>Илгээсэн огноо</th><th>Хүлээн авсан</th><th>Шийдвэрлэсэн</th><th>Хариуцагч ажилтан</th><th>Үйлдэл</th></tr></thead><tbody>
            @foreach ($calls->items() as $call)
                <tr class="{{ auth()->user()->role === 'agent' && $call->status === 'accepted' && $call->accepted_at?->lte(now()->subDays(2)) ? 'overdue' : '' }}"><td>{{ $call->caller_name }}<br><small>{{ $call->caller_phone }}</small></td><td>{{ ['network' => 'Сүлжээ', 'program' => 'Програм', 'hardware' => 'Тоног төхөөрөмж', 'other' => 'Бусад'][$call->call_type] }}</td><td>{{ $call->call_from }}</td><td><span class="badge badge-{{ $call->status }}">{{ ['submitted' => 'Илгээгдсэн', 'accepted' => 'Хүлээн авсан', 'resolved' => 'Шийдвэрлэсэн'][$call->status] }}</span></td><td>{{ $call->requested_at->format('Y-m-d') }}</td><td>{{ $call->accepted_at?->format('Y-m-d') ?? '—' }}</td><td>{{ $call->resolved_at?->format('Y-m-d') ?? '—' }}</td><td>{{ $call->agent?->name ?? 'Хуваарилаагүй' }}</td><td>@if(auth()->user()->role === 'operator' && $call->status === 'submitted')<form method="POST" action="{{ route('calls.assign',$call) }}">@csrf @method('PATCH')<select name="agent_id" required>@foreach($agents as $agent)<option value="{{ $agent->id }}">{{ $agent->name }} — {{ $agent->title }}</option>@endforeach</select><button>Хуваарилах</button></form>@else<a class="button" href="{{ route('calls.show', $call) }}">Дэлгэрэнгүй</a>@endif</td></tr>
            @endforeach
            </tbody></table>
            <div style="margin-top:16px">{{ $calls->links() }}</div>
        @endif
    </div>
@endsection
