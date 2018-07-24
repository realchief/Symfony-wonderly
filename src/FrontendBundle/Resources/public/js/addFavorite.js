/**
 * Checked Favorite Events when Page Load
 * and then Change status by click
 *
 * @param eventString
 * @param uncheckedString
 * @param checkedString
 * @param path
 */
function checkedFavoriteEvents(eventString, uncheckedString, checkedString, path) {

    var events = $('.'+eventString);

    for (i = 0; i < events.length; ++i) {
        if ($(events[i]).attr('checked')) {
            $(events[i]).html(checkedString);
        } else {
            $(events[i]).html(uncheckedString);
        }
    }

    $('.'+eventString).click(function () {
        var eventId = $(this).attr('event');
        var arrayEvents = $('*[event='+eventId+']');
        if($(this).attr('checked')) {
            addFavoriteEvent($(this).attr('event'), false, path);
            for (i = 0; i < arrayEvents.length; ++i) {
                $(arrayEvents[i]).removeAttr('checked');
                $(arrayEvents[i]).html(uncheckedString);
            }
        } else {
            addFavoriteEvent($(this).attr('event'), true, path);
            for (i = 0; i < arrayEvents.length; ++i) {
                $(arrayEvents[i]).attr('checked', 'checked');
                $(arrayEvents[i]).html(checkedString);
            }
        }
    });
}

/**
 * Add or Remove Favorite Event
 * where false remove, true add
 *
 * @param id
 * @param status
 * @param path
 */
function addFavoriteEvent(id, status, path) {
    var data = {id: id};
    if (status) {
        data.status = status;
    }
    $.ajax({
        type: 'POST',
        url : path,
        data: data,
        success: function (data) {
            if (data.redirect) {
                location.replace(data.redirect);
            }
        }
    });
}