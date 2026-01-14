<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Call Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="d-flex">
    @include('layouts.sidebar')

    <main class="p-4 w-100">
        @yield('content')
    </main>
</div>
</body>
</html>
