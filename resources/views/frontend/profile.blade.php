<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $domain->title ?? ucfirst($domain->slug) }} — HubLink</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<style>
		body{font-family:'Inter',sans-serif}
		
		/* Responsive Optimizations */
		@media (max-width: 640px) {
			/* Reduce padding on mobile */
			.bg-white.rounded-3xl {
				padding: 1.5rem !important;
				border-radius: 1.5rem !important;
			}
			
			/* Make buttons full width on mobile */
			a[class*="px-"], button[class*="px-"] {
				width: 100%;
				text-align: center;
				display: block;
			}
			
			/* Adjust text sizes for mobile */
			.text-xl {
				font-size: 1.125rem !important;
			}
			
			.text-2xl {
				font-size: 1.5rem !important;
			}
			
			/* Ensure images are responsive */
			img {
				max-width: 100%;
				height: auto;
			}
			
			/* Stack profile layout on very small screens */
			.flex.items-center.gap-4 {
				flex-direction: column;
				text-align: center;
			}
			
			/* Adjust template card width */
			.max-w-sm {
				max-width: 100% !important;
			}
		}
		
		/* Smooth transitions */
		* {
			transition: all 0.2s ease;
		}
	</style>
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
                                @php
                                    // Build button styles
                                    $buttonClasses = '';
                                    $buttonStyles = '';
                                    
                                    // Add padding classes
                                    if (isset($component->properties['padding'])) {
                                        $buttonClasses .= ' ' . $component->properties['padding'];
                                    }
                                    
                                    // Add font size and weight classes
                                    if (isset($component->properties['fontSize'])) {
                                        $buttonClasses .= ' ' . $component->properties['fontSize'];
                                    }
                                    if (isset($component->properties['fontWeight'])) {
                                        $buttonClasses .= ' ' . $component->properties['fontWeight'];
                                    }
                                    
                                    // Add border radius classes
                                    if (isset($component->properties['borderRadius']) && $component->properties['borderRadius'] !== 'none') {
                                        $buttonClasses .= ' ' . $component->properties['borderRadius'];
                                    }
                                    
                                    // Add border styles
                                    if (isset($component->properties['borderWidth']) && $component->properties['borderWidth'] !== '0') {
                                        $buttonStyles .= 'border: ' . $component->properties['borderWidth'] . 'px solid ' . ($component->properties['borderColor'] ?? '#00c499') . ';';
                                    }
                                    
                                    // Add background and text color styles
                                    $buttonStyles .= 'background-color: ' . ($component->properties['backgroundColor'] ?? '#00c499') . ';';
                                    $buttonStyles .= 'color: ' . ($component->properties['textColor'] ?? '#ffffff') . ';';
                                @endphp
                                <a href="{{ $component->properties['url'] ?? '#' }}" 
                                   class="block w-full {{ trim($buttonClasses) }}"
                                   style="{{ $buttonStyles }}">
                                    {{ $component->properties['text'] ?? 'Button' }}
                                </a>
                                @break
                            @case('image')
                                @php
                                    // Get width, height, and alignment from properties
                                    $width = $component->properties['width'] ?? '100%';
                                    $height = $component->properties['height'] ?? 'auto';
                                    $alignment = $component->properties['alignment'] ?? 'left';
                                    
                                    // Build container classes based on alignment
                                    $containerClasses = '';
                                    if ($alignment === 'center') {
                                        $containerClasses = 'text-center';
                                    } elseif ($alignment === 'right') {
                                        $containerClasses = 'text-right';
                                    } else {
                                        $containerClasses = 'text-left';
                                    }
                                    
                                    // Build image styles
                                    $imageStyles = "width: {$width}; height: {$height};";
                                @endphp
                                <div class="{{ $containerClasses }}">
                                    <div style="display: inline-block; {{ $imageStyles }}">
                                        <img src="{{ $component->properties['src'] ?? '' }}" 
                                             alt="{{ $component->properties['alt'] ?? 'Image' }}" 
                                             style="width: 100%; height: 100%; display: block; object-fit: cover;"
                                             class="rounded">
                                    </div>
                                </div>
                                @break
                            @case('link')
                                @php
                                    // Build link styles
                                    $linkClasses = '';
                                    $linkStyles = '';
                                    
                                    // Add font size and weight classes
                                    if (isset($component->properties['fontSize'])) {
                                        $linkClasses .= ' ' . $component->properties['fontSize'];
                                    }
                                    if (isset($component->properties['fontWeight'])) {
                                        $linkClasses .= ' ' . $component->properties['fontWeight'];
                                    }
                                    
                                    // Add text decoration class
                                    if (isset($component->properties['textDecoration']) && $component->properties['textDecoration'] !== 'no-underline') {
                                        $linkClasses .= ' ' . $component->properties['textDecoration'];
                                    } else if (isset($component->properties['textDecoration']) && $component->properties['textDecoration'] === 'no-underline') {
                                        // Remove underline if explicitly set to no-underline
                                        $linkClasses .= ' no-underline';
                                    }
                                    
                                    // Add text color style
                                    $linkStyles .= 'color: ' . ($component->properties['textColor'] ?? '#00c499') . ';';
                                @endphp
                                <a href="{{ $component->properties['url'] ?? '#' }}" 
                                   class="block {{ trim($linkClasses) }}"
                                   style="{{ $linkStyles }}">
                                    {{ $component->properties['text'] ?? 'Link' }}
                                </a>
                                @break
                            @case('divider')
                                @php
                                    // Get divider properties
                                    $dividerStyle = $component->properties['style'] ?? 'solid';
                                    $dividerColor = $component->properties['color'] ?? '#e5e7eb';
                                    $dividerThickness = $component->properties['thickness'] ?? '1px';
                                    
                                    // Build divider inline styles
                                    $dividerStyles = "border: {$dividerThickness} {$dividerStyle} {$dividerColor}; border-top-width: {$dividerThickness}; border-bottom: 0; border-left: 0; border-right: 0;";
                                @endphp
                                <hr style="{{ $dividerStyles }}">
                                @break
                            @case('profile')
                                @php
                                    $showPhoto = $component->properties['showPhoto'] ?? true;
                                    $showName = $component->properties['showName'] ?? true;
                                    $showUsername = $component->properties['showUsername'] ?? true;
                                    $alignment = $component->properties['alignment'] ?? 'left';
                                    $layout = $component->properties['layout'] ?? 'horizontal';
                                    $photoSize = $component->properties['photoSize'] ?? 'medium';
                                    
                                    // Photo size mapping
                                    $sizeMap = [
                                        'small' => 'w-12 h-12',
                                        'medium' => 'w-16 h-16',
                                        'large' => 'w-24 h-24',
                                        'xlarge' => 'w-32 h-32'
                                    ];
                                    $photoSizeClass = $sizeMap[$photoSize] ?? 'w-16 h-16';
                                    
                                    // Alignment classes
                                    $alignmentClass = '';
                                    if ($alignment === 'center') {
                                        $alignmentClass = 'justify-center text-center';
                                    } elseif ($alignment === 'right') {
                                        $alignmentClass = 'justify-end text-right';
                                    } else {
                                        $alignmentClass = 'justify-start text-left';
                                    }
                                    
                                    // Layout classes
                                    $layoutClass = 'flex items-center gap-4 p-4';
                                    if ($layout === 'vertical') {
                                        $layoutClass = 'flex flex-col items-center gap-4 p-4 text-center';
                                    } elseif ($layout === 'compact') {
                                        $layoutClass = 'flex items-center gap-2 p-2';
                                    } elseif ($layout === 'card') {
                                        $layoutClass = 'flex flex-col items-center gap-4 p-6 bg-gray-50 rounded-lg border';
                                    }
                                @endphp
                                <div class="{{ $layoutClass }} {{ $alignmentClass }}">
                                    @if($showPhoto)
                                        @if($domain->user->profile_photo)
                                            <img src="{{ asset('storage/' . $domain->user->profile_photo) }}" 
                                                 alt="{{ $domain->user->name }}" 
                                                 class="{{ $photoSizeClass }} rounded-full object-cover">
                                        @else
                                            <div class="{{ $photoSizeClass }} rounded-full bg-primary/10 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    @endif
                                    @if($showName || $showUsername)
                                        <div>
                                            @if($showName)
                                                <h3 class="font-semibold text-lg text-gray-900">{{ $domain->user->name }}</h3>
                                            @endif
                                            @if($showUsername)
                                                <p class="text-gray-600 text-sm">{{ '@' . $domain->slug }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @break
                            @case('template')
                                @php
                                    // Format price as Rupiah
                                    $price = $component->properties['price'] ?? 0;
                                    $formattedPrice = 'Rp ' . number_format($price, 0, ',', '.');
                                @endphp
                                <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                                    <img class="w-full h-48 object-cover" src="{{ $component->properties['image'] ?? 'https://placehold.co/400x300' }}" alt="{{ $component->properties['title'] ?? 'Template Image' }}">
                                    <div class="px-6 py-4">
                                        <div class="font-bold text-xl mb-2">{{ $component->properties['title'] ?? 'Template Title' }}</div>
                                        <p class="text-gray-700 text-base">
                                            {{ $component->properties['description'] ?? 'Template description goes here.' }}
                                        </p>
                                    </div>
                                    <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                                        <span class="text-xl font-bold text-primary">{{ $formattedPrice }}</span>
                                        <a href="{{ route('checkout.show', ['domain' => $domain, 'componentId' => $component->id]) }}" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded no-underline">
                                            {{ $component->properties['buttonText'] ?? 'Buy Now' }}
                                        </a>
                                    </div>
                                </div>
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