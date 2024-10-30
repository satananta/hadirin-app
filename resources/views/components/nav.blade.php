{{-- {{dd(url()->current())}} --}}
<a href="{{ $attributes['link'] }}" class="{{ $attributes['active']  }}">
{{$slot}}
</a>