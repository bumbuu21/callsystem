<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="/manifest.webmanifest">
    <title>{{ config('app.name', 'Дуудлага бүртгэл') }}</title>
    <style>
        :root { --blue:#0d6efd; --border:#dee2e6; --ink:#212529; --muted:#6c757d; }
        * { box-sizing: border-box; }
        body { margin: 0; overflow-x: hidden; background: #f8f9fa; color: var(--ink); font-family: Arial, sans-serif; font-size: 15px; }
        nav { width: 100%; min-height: 64px; display: flex; align-items: center; gap: 24px; padding: 10px 24px; background: #fff; border-bottom: 1px solid var(--border); }
        nav a { color: #212529; text-decoration: none; font-weight: 600; } nav a:hover { color: var(--blue); }
        nav .brand { flex: 0 0 auto; font-size: 17px; margin-right: 8px; white-space: nowrap; }
        nav form { margin-left: auto; display: flex; align-items: center; gap: 12px; color: var(--muted); }
        main { max-width: 1240px; margin: 30px auto; padding: 0 24px 40px; }
        h1 { font-size: 29px; margin: 0 0 10px; } h2 { font-size: 21px; margin-top: 0; } p { line-height: 1.5; }
        .card { background: #fff; border: 1px solid #e9ecef; border-radius: 10px; padding: 26px; box-shadow: 0 4px 18px rgba(0,0,0,.045); }
        .grid { display: grid; gap: 18px; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
        .metric { font-size: 30px; font-weight: 700; color: #1f2937; margin-top: 8px; } button { cursor: pointer; }
        .alert { padding: 14px 16px; margin-bottom: 20px; background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; border-radius: 6px; }
        input, select, textarea { width: 100%; margin-top: 7px; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background: #fff; font: inherit; }
        input[type="checkbox"] { width: auto; margin: 0 8px 0 0; padding: 0; vertical-align: middle; }
        input:focus, select:focus, textarea:focus { border-color: #86b7fe; outline: 0; box-shadow: 0 0 0 .2rem rgba(13,110,253,.15); }
        button, .button { display: inline-block; padding: 9px 15px; border: 1px solid var(--blue); border-radius: 6px; background: var(--blue); color: #fff; font: inherit; text-decoration: none; }
        button:hover, .button:hover { background: #0b5ed7; border-color: #0a58ca; color: #fff; }
        nav button { padding: 7px 12px; }
        table { width: 100%; border-collapse: collapse; } th { background: #f8f9fa; font-weight: 700; } th, td { padding: 14px 12px; border-bottom: 1px solid var(--border); text-align: left; vertical-align: middle; }
        tbody tr:hover { background: #f8f9fa; } td a { color: #0d6efd; text-decoration: none; font-weight: 600; }
        tr.overdue td { background: #fde2e2 !important; } .danger { background:#dc3545; border-color:#dc3545; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge-submitted { background: #fff3cd; color: #856404; }.badge-accepted { background: #e7f1ff; color: #0a58ca; }.badge-resolved { background: #d1e7dd; color: #146c43; }
        @media (max-width: 760px) { nav { gap: 12px; flex-wrap: wrap; } nav form { margin-left: 0; } main { padding: 0 14px 30px; margin-top: 18px; } .card { padding: 18px; overflow-x: auto; } h1 { font-size: 24px; } table { min-width: 720px; } }
    </style>
</head>
<body>
    <nav>
        @auth
            <a class="brand" href="{{ route('dashboard') }}"><strong>Дуудлага бүртгэлийн систем</strong></a>
            @if (auth()->user()->role === 'admin') <a href="{{ route('admin.calls') }}">Админ</a> <a href="{{ route('admin.users') }}">Хэрэглэгчид</a> <a href="{{ route('admin.logs') }}">Лог</a> @else <a href="{{ route('calls.index') }}">Дуудлагууд</a> @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <span>{{ auth()->user()->name }} · {{ \App\Support\Labels::role(auth()->user()->role) }}</span>
                <a href="{{ route('profile.edit') }}">Профайл</a>
                <button type="submit">Гарах</button>
            </form>
        @else
            <a class="brand" href="{{ route('login') }}"><strong>Дуудлага бүртгэлийн систем</strong></a>
            <span style="margin-left:auto; color:#6c757d">Нэвтрэх шаардлагатай</span>
        @endauth
    </nav>
    <main>
        @if (session('success')) <div class="alert">{{ session('success') }}</div> @endif
        @yield('content')
    </main>
</body>
<script>if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js');</script>
</html>
