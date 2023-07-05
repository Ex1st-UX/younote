<ul class="list-group mt-3">
    @foreach ($data['items'] as $item)
        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $item['id'] }}">
            {{ $item['title'] }}

            @foreach ($item['tags_list'] as $tag)
                @if ($loop->index == 2)
                    <span class="badge bg-success text-white">...</span>
                    @break
                @else
                    <span class="badge bg-success text-white">{{ $tag }}</span>
                @endif
            @endforeach
        </li>
    @endforeach
</ul>