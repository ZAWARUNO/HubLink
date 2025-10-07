<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $domain->title ?? ucfirst($domain->slug) }} — HubLink</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<style>body{font-family:'Inter',sans-serif}</style>
	<script>
		tailwind.config = { theme: { extend: { colors: { primary: '#00c499', 'primary-dark':'#00a882' } } } }
	</script>
</head>
<body class="min-h-screen bg-gray-50">
	<div class="max-w-xl mx-auto px-4 py-12">
		<div class="bg-white rounded-3xl shadow-sm border p-8">
            @if($domain->components->where('is_published', true)->count() > 0)
                <div class="space-y-6">
                    @foreach($domain->components->where('is_published', true)->sortBy('order') as $component)
                        @switch($component->type)
                            @case('text')
                                @php
                                    $textClasses = '';
                                    if (isset($component->properties['alignment'])) {
                                        $textClasses .= ' text-' . $component->properties['alignment'];
                                    }
                                    if (isset($component->properties['size'])) {
                                        $textClasses .= ' ' . $component->properties['size'];
                                    }
                                    if (isset($component->properties['bold']) && $component->properties['bold']) {
                                        $textClasses .= ' font-bold';
                                    }
                                    if (isset($component->properties['italic']) && $component->properties['italic']) {
                                        $textClasses .= ' italic';
                                    }
                                    if (isset($component->properties['underline']) && $component->properties['underline']) {
                                        $textClasses .= ' underline';
                                    }
                                @endphp
                                <div class="text-content{{ $textClasses }}">
                                    {!! $component->properties['content'] ?? '' !!}
                                </div>
                                @break
                            @case('button')
                                <a href="{{ $component->properties['url'] ?? '#' }}" 
                                   class="block w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-xl font-semibold text-center">
                                    {{ $component->properties['text'] ?? 'Button' }}
                                </a>
                                @break
                            @case('image')
                                <img src="{{ $component->properties['src'] ?? '' }}" 
                                     alt="{{ $component->properties['alt'] ?? 'Image' }}" 
                                     class="w-full h-auto rounded">
                                @break
                            @case('link')
                                <a href="{{ $component->properties['url'] ?? '#' }}" 
                                   class="block text-primary underline">
                                    {{ $component->properties['text'] ?? 'Link' }}
                                </a>
                                @break
                            @case('divider')
                                <hr class="border-gray-300">
                                @break
                        @endswitch
                    @endforeach
                </div>
            @else
                <!-- Default content if no components are published -->
                <div class="text-center">
                    <div class="w-24 h-24 rounded-full bg-primary/10 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 110 8 4 4 0 010-8zm0 8c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $domain->title ?? '@'.$domain->slug }}</h1>
                    <p class="text-gray-600 mt-2">{{ $domain->bio ?? 'Profil HubLink Anda' }}</p>
                    <div class="mt-6 space-y-3">
                        <a href="#" class="block w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-xl font-semibold">Tombol Utama</a>
                        <a href="#" class="block w-full border border-primary text-primary hover:bg-primary hover:text-white py-3 rounded-xl font-semibold">Tombol Kedua</a>
                    </div>
                </div>
            @endif
		</div>
	</div>
</body>
</html>