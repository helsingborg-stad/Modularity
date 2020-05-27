<?php
    $classes = (isset($class) && is_string($class)) ? $class : '';
    $classes = (isset($icon) && is_string($icon)) ? $classes . ' pricon pricon-' . $icon . ' pricon-space-right' : $classes;
?>


{{-- Tooltip --}}
@if (isset($compact) && $compact) <span data-tooltip="{{ $slot }}"> @endif

    <a
        @if (isset($itemprop) && $itemprop) itemprop="{{ $itemprop }}" @endif
        @if (isset($href) && $href) href="{{ $href }}" @endif
        @if (isset($classes) && $classes) class="{{ $classes }}" @endif
    >
        @if (isset($compact) && $compact)
            <span class="hidden">
        @endif

        {{$slot}}

        @if (isset($compact) && $compact)
            </span>
        @endif
    </a>

{{-- Tooltip end --}}
@if (isset($compact) && $compact) </span> @endif

