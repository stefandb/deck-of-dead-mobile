<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Dead of Deck') }}</title>

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <main class="nativephp-safe-area">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

        <native:bottom-nav>
            <native:bottom-nav-item
                id="home"
                icon="house.fill"
                label="Home"
                url="{{ route('home') }}"
                :active="request()->is('/')"
            />
            <native:bottom-nav-item
                id="library"
                icon="list.bullet"
                label="Library"
                url="{{ route('library') }}"
                :active="request()->is('library*')"
            />
            <native:bottom-nav-item
                id="history"
                icon="clock"
                label="Historie"
                url="{{ route('history') }}"
                :active="request()->is('history*')"
            />
        </native:bottom-nav>

        @livewireScripts
    </body>
</html>
