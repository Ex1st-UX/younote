import $ from "jquery";
import {orderBy} from "lodash/collection";

class Component {
    constructor() {
        this.popupNode = $('.popup');
        this.popupContentNode = $('.popup-content');
        this.taskListContentNode = $('.task_content');
        this.createTaskFormId = '#new_task__form';

        this.init();
    }

    init() {
        this.events();
        this.loadTaskList();
    }

    events() {
        $('.list-group-item').on('click', this.openEditor.bind(this));
        $('.task_add__button').on('click', this.addTaskSlide.bind(this));
        $('.task_filter__button').on('click', this.filterOpen.bind(this));

        $(document).on('click', '#popup_close__button', this.closeEditor.bind(this));
        $(document).on('click', '.add_custom_tag__button', this.addCustomTag.bind(this))
        $(document).on('submit', this.createTaskFormId, this.addTaskSubmit.bind(this))
        $(document).on('submit', '#filter__form', this.onFilterSubmit.bind(this))
        $(document).on('click', '.list-group-item', this.selectTask.bind(this))
        $(document).on('change', '#sort__select', this.onSortChange.bind(this))
    }

    loadTaskList(params = {}) {
        let orderBy = (this.orderBy) ? this.orderBy : 'created_at';

        this.sendRequest(
            '/tasks/get/list/sort/' + orderBy,
            this.flterFormdata,
            {contentType: 'application/json', ...params},
            (response) => {
                this.getTemplate('list_tasks', JSON.stringify(response))
                    .then((node) => {
                        this.render(node, this.taskListContentNode);
                    });
            }
        );
    }

    onFilterSubmit(event) {
        event.preventDefault();
        this.flterFormdata = new FormData(document.querySelector('#filter__form'));

        this.loadTaskList({processData: false, contentType: false});
        this.closeEditor();
    }

    onSortChange() {
        this.orderBy = $(event.target).val();
        this.loadTaskList({processData: false, contentType: false});
    }

    filterOpen() {
        this.getTemplate('filter', this.flterFormdata, false)
            .then((node) => {
                this.render(node, this.popupContentNode);
                this.openEditor();
            });
    }

    selectTask() {
        let taskId = $(event.target).data('id');

        if (!$(event.target).hasClass('list-group-item')) {
            return;
        }

        this.openEditor();
        this.sendRequest(
            '/tasks/get/id/' + taskId,
            {},
            {contentType: 'application/json',},
            (response) => {
                this.getTemplate('task', JSON.stringify(response))
                    .then((node) => {
                        this.render(node, this.popupContentNode);
                    });
            }
        );
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
            (response) => {
                this.getTemplate('task', JSON.stringify(response))
                    .then((node) => {
                        this.loadTaskList({processData: false, contentType: false});
                        this.render(node, this.popupContentNode);
                        this.openEditor();
                    });
            }
        );
    }

    addTaskSlide() {
        this.getTemplate('create_task')
            .then((node) => {
                this.render(node, this.popupContentNode);
                this.openEditor();
            })
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

    addPreloader() {
        document.getElementById('preloader').style.display = 'flex';
    }

    removePreloader() {
        document.getElementById('preloader').style.display = 'none';
    }

    async getTemplate(templateName, data = {}, contentType = 'application/json') {
        let node;

        await this.sendRequest(
            'templates/render/' + templateName,
            data,
            {
                contentType: contentType,
                processData: false
            },
            (response) => {
                node = response;
            }
        );

        return $(node);
    }

    sendRequest(url, data, params = {}, callback) {
        let defaultParams = {
            url: url,
            type: 'POST',
            data: data,
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

    /**
     * Параметры (ноды) ожидаются в обьекте jQuery (полученные/созданные через "$")
     * @param node
     * @param targetNode
     */
    render(node, targetNode) {
        targetNode.empty();
        return targetNode.append(node);
    }
}

$(document).ready(() => new Component());


