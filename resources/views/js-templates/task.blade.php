<div>
    <div class="editor_content__title d-flex justify-content-between align-items-center">
        <h2>{{ $data['title'] }}</h2>
        <button type="button" id="popup_close__button"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="tags">
        @foreach ($data['tags_list'] as $tag)
            <span class="badge bg-success text-white">{{ $tag }}</span>
        @endforeach
    </div>
    <div class="task_description__text">
        <p>{{ $data['text'] }}</p>
    </div>
    <div class="task_image__wrapper">
        <div class="container">
            <div class="row">
                @foreach ($data['img'] as $img)
                    <div class="col">
                        <a href="{{ $img['picture'] }}" target="_blank">
                            <img src="{{ $img['preview'] }}" class="img-fluid" alt="Responsive image">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
