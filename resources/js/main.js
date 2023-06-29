import $ from "jquery";

class Component {
    constructor(params) {
        this.popupNode = $('.popup');
        this.popupContentNode = $('.popup-content');
        this.taskListContentNode = $('.task_content');
        this.addTaskButtonNode = $('.task_add__button');
        this.createTaskFormId = '#new_task__form';
        this.userId = params.userId;

        this.init();

        if (params.loadTask) {
            this.taskList();
        }
    }

    init() {
        $('.list-group-item').on('click', this.openEditor.bind(this));
        this.addTaskButtonNode.on('click', this.addTaskSlide.bind(this));

        $(document).on('click', '#popup_close__button', this.closeEditor.bind(this));
        $(document).on('click', '.add_custom_tag__button', this.addCustomTag.bind(this))
        $(document).on('submit', this.createTaskFormId, this.addTaskSubmit.bind(this))
        $(document).on('click', '.list-group-item', this.selectTask.bind(this))
    }

    selectTask() {
        if (!$(event.target).hasClass('list-group-item')) {
            return;
        }

        let taskId = $(event.target).data('id');
        let _this = this;

        this.openEditor();
        this.sendRequest(
            '/tasks/get/id/' + taskId,
            {},
            {},
            function (response) {
                _this.removePreloader();
                _this.taskSlideHtml(response);
            }
        );
    }

    sendRequest(url, data, params = {}, callback) {
        let _this = this;
        let defaultParams = {
            url: url,
            type: 'POST',
            data: data,
            contentType: 'application/json',
        };

        let mergedParams = Object.assign({}, defaultParams, params);

        return $.ajax({
            ...mergedParams,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: callback
        });
    }

    addPreloader() {
        document.getElementById('preloader').style.display = 'flex';
    }

    removePreloader() {
        document.getElementById('preloader').style.display = 'none';
    }

    taskList() {
        let _this = this;

        this.sendRequest(
            '/tasks/get/list',
            {},
            {},
            function (response) {
                let tasks = response;
                let node = $('<ul class="list-group mt-3"></ul>');

                tasks.forEach(function (task) {
                    let tags = task.tags_list;
                    let tagsNode = $('<div class="test"></div>');
                    const maxBadges = 3;
                    let current = 0;
                    let tagItem;

                    if (tags && tags.length > 0) {
                        tags.forEach(function (tag) {

                            if (current < maxBadges) {
                                if (current == 2) {
                                    tagItem = $('<span class="badge bg-success text-white"></span>').text('...');
                                } else {
                                    tagItem = $('<span class="badge bg-success text-white"></span>').text(tag);
                                }

                                tagsNode.append(tagItem);
                                current++;
                            }
                        });
                    }

                    let listItem = $(`
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${task.id}">
                            ${task.title}
                            ${tagsNode.html()}
                        </li>
                    `);
                    node.append(listItem);
                });

                _this.render(node, _this.taskListContentNode);
            }
        );
    }

    taskSlideHtml(data) {
        const {title, text, tags_list} = data;
        let tagsNode = null;

        if (tags_list && tags_list.length > 0) {
            const tagsHtml = tags_list.map(tag =>
                `<span class="badge bg-success text-white">${tag}</span>`
            )
                .join("");

            tagsNode = $(`<div class="tags">${tagsHtml}</div>`);
        }

        const ImagesNode = Object.values(data.img).map(image => `
                    <div class="col">
                        <a href="${image.picture}" target="_blank">
                        <img src="${image.preview}" class="img-fluid" alt="Responsive image">
                        </a>
                    </div>
          `)
            .join("");

        const node = $(`       
                <h2>${title}</h2>
                ${tagsNode ? tagsNode[0].outerHTML : ""}
                <div class="task_description__text">
                    <p>${text}</p>
                </div>
                <div class="task_image__wrapper">
                    <div class="container">
                        <div class="row">
                            ${ImagesNode}
                        </div>
                    </div>
                </div>
        `);
        this.render(node, this.popupContentNode);
    }

    addTaskSubmit(e) {
        e.preventDefault();
        let form = document.querySelector(this.createTaskFormId);
        let formData = new FormData(form);
        let _this = this;

        this.sendRequest(
            '/tasks/create',
            formData,
            {
                processData: false,
                contentType: false,
                beforeSend: function () {
                    _this.closeEditor();
                },
            },
            function (response) {
                let task = response.data;

                if (response.success) {
                    _this.openEditor();
                    _this.taskSlideHtml(task);
                } else {
                    alert('error');
                }
            }
        );
    }

    addTaskSlide() {
        const node = $(`
        <form enctype="multipart/form-data" id="new_task__form">
          <div class="editor_content__title d-flex justify-content-between align-items-center">
            <h3>Add new task</h3>
            <button type="button" id="popup_close__button"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="form-group row editor_content__wrapper">
            <div class="col-sm-10">
              <input type="text" class="form-control" name="title_task__input" id="title_task__input" placeholder="Task title here ..." required>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-10">
              <textarea class="form-control" name="text_task__input" rows="8" placeholder="Type comment ..." required></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <h5>Add custom tags <a href="javascript:void(0)"><span class="badge bg-success text-white add_custom_tag__button">New</span></a></h5>
            </div>
          </div>
          <div class="form-group row tag_name_input__wrapper">
          </div>
          <div class="custom-file task_image__add">
            <input type="file" class="custom-file-input" id="image_task__input" multiple name="image_task__input[]">
            <label class="custom-file-label" for="image_task__input">Press shift to choose few image (JPG, JPEG, and PNG)</label>
          </div>
          <div class="form-group row">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-success btn-block">Save</button>
            </div>
          </div>
        </form>
      `);

        this.render(node, this.popupContentNode);
        this.openEditor();
    }

    addCustomTag() {
        const $inputWrapperNode = $('.tag_name_input__wrapper');
        const html = $('<div class="col-sm-10 tag_name_input__content">' + '   <input type="text" class="form-control" name="tag_task__input[]" placeholder="Tag name ...">' + '</div>');

        $inputWrapperNode.append(html);
        $('.tag_name_input__content').fadeIn(400);
    }

    openEditor() {
        this.popupNode.animate({width: 'toggle'}, 200);
    }

    closeEditor() {
        this.popupNode.animate({width: 'toggle'}, 200);
    }

    /**
     * Параметры ожидаются в формате jQuery (полученные/созданные через "$")
     * @param node
     * @param targetNode
     */
    render(node, targetNode) {
        targetNode.empty();
        return targetNode.append(node);
    }
}

$(document).ready(function () {
    new Component({
        userId: userId,
        loadTask: true
    });
});


