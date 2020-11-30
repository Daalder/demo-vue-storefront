class Loader {
    constructor($parent) {
        this.html = '<div id="loader"></div>';
        this.$parent = $parent;
    }

    show() {
        if (this.$parent.find('#loader').length === 0) {
            this.$parent.append(this.html);
        }
        this.$parent.find('#loader').fadeIn();
    }

    hide() {
        this.$parent.find('#loader').fadeOut();
    }

    setFixed() {
        this.$parent.find('.spinnercontainer').addClass('fixed');
    }
}

export default Loader;
