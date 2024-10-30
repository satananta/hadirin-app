<button {{ $attributes->merge(['class' => 'text-center p-3 my-3 rounded-full']) }}>
    {{$slot}}
</button>