<form id="filter__form">
    <div class="editor_content__title d-flex justify-content-between align-items-center">
        <h3>Filter</h3>
        <button type="button" id="popup_close__button"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <input type="text" class="form-control" name="search_q" id="search_q"
                   placeholder="Text search query here ..." value="{{ (isset($data['search_q'])) ? $data['search_q'] : '' }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <label for="disabledSelect">Filter by tags</label>
            <select class="custom-select" name="filter__tags">
                <option value="" selected>All</option>
                @foreach (App\Http\Controllers\TaskController::getTags() as $tag)
                    <option value="{{ $tag }}">{{ $tag }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group datepicker__input">
        <label for="filter__start_date">Created after:</label>
        <input type="date" class="form-control" id="filter__start_date" name="filter__start_date" value="{{ isset($data['filter__start_date']) ? $data['filter__start_date'] : '' }}">
    </div>
    <div class="form-group datepicker__input">
        <label for="datepicker">Created before:</label>
        <input type="date" class="form-control datepicker" id="filter__start_date" name="filter__end_date" value="{{ isset($data['filter__end_date']) ? $data['filter__end_date'] : '' }}">
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-success btn-block">Save</button>
        </div>
    </div>
</form>