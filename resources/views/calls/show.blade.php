@extends('layouts.app')

@section('content')
    <p><a href="{{ route('calls.index') }}">← Дуудлагууд руу буцах</a></p>
    <div class="card">
        <h1>Дуудлага #{{ $call->id }}</h1>
        <p><strong>Төлөв:</strong> <span class="badge badge-{{ $call->status }}">{{ ['submitted' => 'Илгээгдсэн', 'accepted' => 'Хүлээн авсан', 'resolved' => 'Шийдвэрлэсэн'][$call->status] }}</span></p>
        <p><strong>Төрөл:</strong> {{ ['network' => 'Сүлжээ', 'program' => 'Програм', 'hardware' => 'Тоног төхөөрөмж', 'other' => 'Бусад'][$call->call_type] }}</p>
        <p><strong>Байршил:</strong> {{ $call->call_from }}</p>
        <p><strong>Илгээгч:</strong> {{ $call->caller_name }} @if ($call->caller_phone) ({{ $call->caller_phone }}) @endif</p>
        <p><strong>Илгээсэн:</strong> {{ $call->requested_at->format('Y-m-d H:i') }}</p>
        <p><strong>Тайлбар:</strong><br>{!! nl2br(e($call->description)) !!}</p>
        @if ($call->photo_path) <p><a href="{{ Storage::disk('public')->url($call->photo_path) }}" target="_blank">Хавсаргасан зургийг нээх</a></p> @endif
        @if ($call->agent) <p><strong>Хуваарилагдсан инженер:</strong> {{ $call->agent->name }}</p> @endif
        @if ($call->agent_description) <p><strong>Инженерийн тайлбар:</strong><br>{!! nl2br(e($call->agent_description)) !!}</p> @endif
        @if ($call->client_comment) <p><strong>Харилцагчийн сэтгэгдэл:</strong><br>{!! nl2br(e($call->client_comment)) !!}</p> @endif
        @if (auth()->user()->role === 'operator' && $call->status === 'submitted')
            <hr>
            <h2>Инженер хуваарилах</h2>
            <form method="POST" action="{{ route('calls.assign', $call) }}">
                @csrf @method('PATCH')
                <p><label>Инженер
                    <select name="agent_id" required>
                        <option value="">Сонгоно уу</option>
                        @foreach ($agents as $agent) <option value="{{ $agent->id }}">{{ $agent->name }}{{ $agent->title ? ' — '.$agent->title : '' }}</option> @endforeach
                    </select>
                </label></p>
                @error('agent_id') <p style="color:#b42318">{{ $message }}</p> @enderror
                <button type="submit">Хуваарилах</button>
            </form>
        @endif
        @if (auth()->user()->role === 'agent' && $call->agent?->user_id === auth()->id() && $call->status === 'accepted')
            <hr>
            <h2>Шийдвэрлэсэн тухай тэмдэглэл</h2>
            <form method="POST" action="{{ route('calls.resolve', $call) }}">
                @csrf @method('PATCH')
                <p><label>Ямар арга хэмжээ авсан бэ?
                    <textarea name="agent_description" rows="5" required></textarea>
                </label></p>
                @error('agent_description') <p style="color:#b42318">{{ $message }}</p> @enderror
                <button type="submit">Шийдвэрлэсэн болгох</button>
            </form>
        @endif
        @if (auth()->user()->role === 'customer' && $call->user_id === auth()->id() && $call->status === 'resolved' && ! $call->client_comment)
            <hr>
            <h2>Сэтгэгдэл үлдээх</h2>
            <form method="POST" action="{{ route('calls.comment', $call) }}">
                @csrf @method('PATCH')
                <p><label>Үйлчилгээний талаар сэтгэгдлээ бичнэ үү.
                    <textarea name="client_comment" rows="4" required></textarea>
                </label></p>
                @error('client_comment') <p style="color:#b42318">{{ $message }}</p> @enderror
                <button type="submit">Сэтгэгдэл хадгалах</button>
            </form>
        @endif
    </div>
@endsection
