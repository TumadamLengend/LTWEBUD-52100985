function wait(selector, disable, buttonText) {
    var button = $(selector);
    if (disable) {
        button.prop('disabled', true);
        button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');
    } else {
        button.prop('disabled', false);
        button.html(buttonText);
    }
}