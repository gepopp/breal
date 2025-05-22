<form action="{!! $url !!}" {!! $attributes !!}>
    @csrf
    <button type="submit" class="{!! $basename !!}__link bg-white text-logo py-2 px-4 rounded-md">
        <span class="{!! $basename !!}__label">{{ $label }}</span>
    </button>
</form>
