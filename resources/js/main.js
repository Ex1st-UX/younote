class Component {
    constructor() {
        this.popupNode = $('.popup');
        this.init();
    }

    init() {
        $('.popup').on('click', this.closeTask.bind(this));
        $('.list-group-item').on('click', this.openTask.bind(this));
    }

    openTask() {
        this.popupNode.animate({width:'toggle'}, 200);
    }

    closeTask() {
        this.popupNode.animate({width:'toggle'}, 200);
    }
}

$(document).ready(new Component);
