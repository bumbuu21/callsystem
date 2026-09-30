@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 720px; margin: auto;">
        <h1>Шинэ дуудлага илгээх</h1>
        <p>Асуудлын мэдээллийг аль болох дэлгэрэнгүй оруулна уу.</p>
        <form method="POST" action="{{ route('calls.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid"><p><label>Огноо<input value="{{ now()->format('Y-m-d') }}" readonly></label></p><p><label>Харилцагчийн нэр<input value="{{ auth()->user()->name }}" readonly></label></p><p><label>Утас<input value="{{ auth()->user()->phone }}" readonly></label></p></div>
            <p><label>Дуудлагын төрөл
                <select name="call_type" required>
                    <option value="">Сонгоно уу</option>
                    <option value="network" @selected(old('call_type') === 'network')>Сүлжээ</option>
                    <option value="program" @selected(old('call_type') === 'program')>Програм</option>
                    <option value="hardware" @selected(old('call_type') === 'hardware')>Тоног төхөөрөмж</option>
                    <option value="other" @selected(old('call_type') === 'other')>Бусад</option>
                </select>
            </label> @error('call_type') <span style="color:#b42318">{{ $message }}</span> @enderror</p>
            <p><label>Байршил / газар, хэлтэс
                <select name="call_from" required><option value="">Сонгоно уу</option>@foreach(config('locations.departments') as $department)<option @selected(old('call_from') === $department)>{{ $department }}</option>@endforeach</select>
            </label> @error('call_from') <span style="color:#b42318">{{ $message }}</span> @enderror</p>
            <p><label>Асуудлын дэлгэрэнгүй тайлбар
                <textarea name="description" rows="7" required>{{ old('description') }}</textarea>
            </label> @error('description') <span style="color:#b42318">{{ $message }}</span> @enderror</p>
            <p><label>Зураг хавсаргах (заавал биш, дээд хэмжээ 5 MB)
                <input type="file" name="photo" accept="image/*">
            </label> @error('photo') <span style="color:#b42318">{{ $message }}</span> @enderror</p>
            <button type="submit">Дуудлага илгээх</button>
            <a href="{{ route('calls.index') }}" style="margin-left:12px">Болих</a>
        </form>
    </div>
@endsection
