$(document).ready(function () {
    $('#messageSent').modal('show');
    $('#messageSentClose').click(function () {
        $('#messageSent').modal('hide');
    })
});
$('.contact').click(function() {
    var setTo = $(this).attr('setto');
    if (setTo === 'organizer') {
        $('#contact_message_form_setTo').val($(this).attr('id'));
        $('.contact-form-title').html('<h1>'+$(this).attr('name')+'</h1><small>Send me message</small>');
    } else if (setTo === 'admin') {
        $('#contact_message_form_setTo').val('');
        $('.contact-form-title').html('<h4>Event information incorrect</h4><h3>Notify us here</h3>');
    }
    $('#contactForm').fadeToggle();
});
$(document).mouseup(function (e) {
    var container = $("#contactForm");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.fadeOut();
    }
});