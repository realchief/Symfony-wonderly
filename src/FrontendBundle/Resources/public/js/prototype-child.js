$(document).ready(function() {

    // Get the ul that holds the collection of tags
    var $collectionHolder = $('.child-list');


    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $('.child-new').on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addCollectionItemForm($collectionHolder);
    });

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('div.form-horizontal').each(function() {
        addTagFormDeleteLink($(this));
    });

});

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#" class="delete-child">Delete</a>');
    var del = $tagFormLi.find('a.delete-child');
    if(del.length == 0) {
        $tagFormLi.append($removeFormA);
    }


    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

function addCollectionItemForm($collectionHolder) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);
    $collectionHolder.append(newForm);

    $collectionHolder.find('div.form-horizontal').each(function() {
        addTagFormDeleteLink($(this));
    });
};