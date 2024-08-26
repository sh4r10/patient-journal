@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 text-yellow-400
            px-4 text-sm font-medium leading-5 text-yellow-400
            focus:outline-none transition duration-150 ease-in-out border-b-4
            border-yellow-400'
            : 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-slate-400
            focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
