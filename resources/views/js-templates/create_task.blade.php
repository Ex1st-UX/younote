<form enctype="multipart/form-data" id="new_task__form">
    <div class="editor_content__title d-flex justify-content-between align-items-center">
        <h3>Add new task</h3>
        <button type="button" id="popup_close__button"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="form-group row editor_content__wrapper">
        <div class="col-sm-10">
            <input type="text" class="form-control" name="title_task__input" id="title_task__input"
                   placeholder="Task title here ..." required>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <textarea class="form-control" name="text_task__input" rows="8" placeholder="Type comment ..."
                      required></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10">
            <h5>Add custom tags <a href="javascript:void(0)"><span
                            class="badge bg-success text-white add_custom_tag__button">New</span></a></h5>
        </div>
    </div>
    <div class="form-group row tag_name_input__wrapper">
    </div>
    <div class="custom-file task_image__add">
        <input type="file" class="custom-file-input" id="image_task__input" multiple name="image_task__input[]">
        <label class="custom-file-label" for="image_task__input">Press shift to choose few image (JPG, JPEG, and
            PNG)</label>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-success btn-block">Save</button>
        </div>
    </div>
</form>