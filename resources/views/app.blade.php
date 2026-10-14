<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>Official Web Portal of Department of Sports, Government of Uttar Pradesh, India</title>

    <!-- Theme & Portal Styles -->
    <link href="{{ asset('admin/css/bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/font.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/all.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/custom_theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/default.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/responsive.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Vite & Asset Management -->
    @if (file_exists(public_path('hot')))
    @php
    $viteUrl = trim(file_get_contents(public_path('hot')));
    @endphp
    <script type="module">
        import RefreshRuntime from "{{ $viteUrl }}/@react-refresh";
        RefreshRuntime.injectIntoGlobalHook(window);
        window.$RefreshReg$ = () => {};
        window.$RefreshSig$ = () => (type) => type;
        window.__vite_plugin_react_preamble_installed__ = true;
    </script>
    <script type="module" src="{{ $viteUrl }}/@vite/client"></script>
    <link rel="stylesheet" href="{{ $viteUrl }}/resources/css/app.css" />
    <script type="module" src="{{ $viteUrl }}/resources/js/app.jsx"></script>
    @elseif (file_exists(public_path('build/manifest.json')))
    @php
    $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    @endphp
    @if (isset($manifest['resources/css/app.css']['file']))
    <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}" />
    @endif
    @if (isset($manifest['resources/js/app.jsx']['css']))
    @foreach ($manifest['resources/js/app.jsx']['css'] as $cssFile)
    <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}" />
    @endforeach
    @endif
    @if (isset($manifest['resources/js/app.jsx']['file']))
    <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.jsx']['file']) }}"></script>
    @endif
    @elseif (file_exists(public_path('mix-manifest.json')))
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    @else
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    @endif

    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>