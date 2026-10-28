<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Master Ekspedisi') - Ekspedisi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="header">
        <h1>@yield('heading', 'MASTER ADMIN - EKSPEDISI')</h1>
        <div class="line"></div>
        @yield('header-action')
    </div>

    <div class="main-container-page">
        @if (session('success'))
            <div class="notice" role="status">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
