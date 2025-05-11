@php
$manifest = null;
$buildDirectory = "build";

// First try the standard manifest location
$manifestPath = public_path("{$buildDirectory}/manifest.json");
if (!file_exists($manifestPath)) {
    // Then try the Vite 4+ location
    $manifestPath = public_path("{$buildDirectory}/.vite/manifest.json");
}

if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
}

// Get current URL scheme and host for local dev
$urlScheme = request()->getScheme();
$urlHost = request()->getHost();
$urlPort = request()->getPort();
$portSuffix = ($urlPort == 80 || $urlPort == 443) ? '' : ":{$urlPort}";
$baseUrl = "{$urlScheme}://{$urlHost}{$portSuffix}";
@endphp

@if (app()->environment('local') && !$manifest)
    {{-- In development, use the Vite development server --}}
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    
    @foreach ($inputs as $input)
        @if (Str::endsWith($input, '.css'))
            <link rel="stylesheet" href="http://localhost:5173/{{ $input }}" />
        @elseif (Str::endsWith($input, '.js'))
            <script type="module" src="http://localhost:5173/{{ $input }}"></script>
        @endif
    @endforeach
@else
    {{-- In production, use the compiled assets --}}
    @foreach ($inputs as $input)
        @php
            $manifestKey = $input;
            if (!isset($manifest[$manifestKey])) {
                // For Vite 4+, the key might have a leading slash
                $manifestKey = ltrim($input, '/');
            }
        @endphp
        
        @if (isset($manifest[$manifestKey]))
            @if (Str::endsWith($input, '.css'))
                <link rel="stylesheet" href="{{ asset("{$buildDirectory}/{$manifest[$manifestKey]['file']}") }}" />
            @elseif (Str::endsWith($input, '.js'))
                <script type="module" src="{{ asset("{$buildDirectory}/{$manifest[$manifestKey]['file']}") }}"></script>
            @endif
            
            @if (isset($manifest[$manifestKey]['css']))
                @foreach ($manifest[$manifestKey]['css'] as $css)
                    <link rel="stylesheet" href="{{ asset("{$buildDirectory}/{$css}") }}" />
                @endforeach
            @endif
        @endif
    @endforeach
@endif 