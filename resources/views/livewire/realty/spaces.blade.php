<div>
    <ul class="">
        @foreach($formatted_spaces as $key => $value)
            <li class="flex justify-between !ml-0">
                <span>{{ $key }}</span>
                <span>{{ $value  }}</span>
            </li>
        @endforeach
    </ul>
</div>
