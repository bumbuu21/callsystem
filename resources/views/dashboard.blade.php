@extends('layouts.app')

@section('content')
    <h1>Хяналтын самбар</h1>
    @if (auth()->user()->role === 'customer')
        <div class="card" style="margin-bottom:20px"><h2>Дуудлага илгээх хэрэгтэй юу?</h2><p>Асуудлын төрөл, газар/хэлтэс, тайлбараа оруулаад илгээнэ. Оператор тохирох инженерийг хуваарилна.</p><a class="button" href="{{ route('calls.create') }}">+ Шинэ дуудлага илгээх</a> <a href="{{ route('calls.index') }}">Миний дуудлагууд</a></div>
    @elseif (auth()->user()->role === 'operator')
        <div class="card" style="margin-bottom:20px"><h2>Операторын ажил</h2><p>Илгээгдсэн дуудлагыг нээж, тохирох инженерийг сонгоод хуваарилна.</p><a class="button" href="{{ route('calls.index') }}">Ирсэн дуудлагуудыг харах</a></div>
    @elseif (auth()->user()->role === 'agent')
        <div class="card" style="margin-bottom:20px"><h2>Инженерийн ажил</h2><p>Танд хуваарилагдсан дуудлагыг шалгаж, шийдсэний дараа тайлбар бичиж “Шийдвэрлэсэн” төлөвт оруулна.</p><a class="button" href="{{ route('calls.index') }}">Миний хариуцах дуудлагууд</a></div>
    @else
        <div class="card" style="margin-bottom:20px"><h2>Админы ажил</h2><p>Бүх дуудлага, статистик, тайлан, хэрэглэгч болон системийн логийг эндээс удирдана.</p><a class="button" href="{{ route('admin.calls') }}">Админы самбар нээх</a></div>
    @endif
    <div class="grid">
        <div class="card">Нийт дуудлага<div class="metric">{{ $totalCalls }}</div></div>
        <div class="card">Илгээгдсэн<div class="metric">{{ $submittedCalls }}</div></div>
        <div class="card">Хүлээн авсан<div class="metric">{{ $acceptedCalls }}</div></div>
        <div class="card">Шийдвэрлэсэн<div class="metric">{{ $resolvedCalls }}</div></div>
    </div>
@endsection
