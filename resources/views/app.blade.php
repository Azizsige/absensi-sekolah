<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title inertia>{{ config('app.name', 'Absensi Web') }}</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

    @inertiaHead
</head>

<body class="font-sans antialiased bg-gray-50">
    @inertia
</body>

</html>
