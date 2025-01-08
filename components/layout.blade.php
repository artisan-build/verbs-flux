<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Powered by Hallway.fm') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    @fluxStyles
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">


<flux:header class="!block bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:navbar class="lg:hidden w-full">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

        <flux:spacer/>

    </flux:navbar>
</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>


@stack('modals')

@livewireScripts
@fluxScripts
</body>
</html>
