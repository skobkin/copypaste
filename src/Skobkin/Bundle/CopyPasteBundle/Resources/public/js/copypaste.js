$(function () {
    var $qr_link = $('#paste-qr-code');
    $qr_link.popover({
        content: '<img src="' + $qr_link.data('qr-url') + '" class="img-rounded"/>',
        placement: 'bottom',
        trigger: 'focus',
        html: true
    });
});