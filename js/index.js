(function() {
    let input_ = document.getElementsByTagName('input');
    for (var i = 0; i < input_.length; i++) {
        let placeholder = input_[i].placeholder;
        input_[i].onfocus = function() { this.placeholder = ""; }
        input_[i].onblur = function() { this.placeholder = placeholder; }
    }
})();