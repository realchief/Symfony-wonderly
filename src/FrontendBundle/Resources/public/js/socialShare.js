
/**
 * Share button for event
 * @param path
 * @param title
 */
function showModalShare (path, title) {
    $('.share-modal-show').modal('show');
    $('.modal-backdrop').css('opacity', '0.8');
    $('#fb-event').attr('href', 'https://www.facebook.com/sharer.php?u=' + path + '&t=Wonderly');
    $('#twit-event').attr('href', 'https://twitter.com/intent/tweet?text=Wonderly ' + path + '&source=webclient');
    $('#sms-event').attr('href', 'sms:?body=' + path);
    $('#email-event').attr('href', 'mailto:?to=&subject=I%27d%20like%20to%20share%20a%20link%20with%20you&body=' + path);
    $('#reddit-event').attr('href', 'https://www.reddit.com/submit?url=' + path + '&title=' + title);
}