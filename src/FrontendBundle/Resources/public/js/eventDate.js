var eventFormDate = {
    periodic: '',
    range: '',
    multiple: ''
};
$(document).ready(function() {
    $('#mdp-range-modal').multiDatesPicker({
        mode: 'freeRange',
        numberOfMonths: [12, 1],
    });
    $('#mdp-date-modal').multiDatesPicker({
        numberOfMonths: [12, 1],
    });
});

$('#eventDate').val('');

function eventFormDateSave() {
    //
    // Select periodic week or month
    //
    $('#periodic-date-select').change(function () {
        if ($(this).val() === 'month') {
            $('.periodic-date-week').addClass('hidden');
            $('.periodic-date-month').removeClass('hidden');
        } else if ($(this).val() === 'week') {
            $('.periodic-date-month').addClass('hidden');
            $('.periodic-date-week').removeClass('hidden');
        }
    });
    //
    // Periodic date save Month or Week
    //
    $('#periodic-date-save-month').click(function () {
        var selected = [];
        $('.periodic-date-month input:checked').each(function() {
            selected.push($(this).attr('value'));
        });
        eventFormDate.periodic = selected;
        $('#periodic-date-modal').modal('hide');
        showSelectedPeriodic(selected)
    });
    $('#periodic-date-save-week').click(function () {
        var selected = [];
        $('.periodic-date-week input:checked').each(function() {
            selected.push($(this).attr('value'));
        });
        eventFormDate.periodic = selected;
        $('#periodic-date-modal').modal('hide');
        showSelectedPeriodic(selected)
    });
    //
    // Custom multiple dates save
    //
    $('#custom-date-save').click(function () {
        $('#custom-date-modal').modal('hide');
        var multipleDatesArr = $('#mdp-date-modal').multiDatesPicker('getDates');
        showMultipleDates(multipleDatesArr);
        eventFormDate.multiple = multipleDatesArr;
    });
    //
    // Custom range save
    //
    $('#custom-range-save').click(function () {
        $('#custom-range-modal').modal('hide');
        var firstAndLast = function(arr) {
            return [arr[0], arr[arr.length - 1]];
        }
        var arr = firstAndLast($('#mdp-range-modal').multiDatesPicker('getDates'));
        $('.date-hide').html('Dates Range: Start Date: '+arr[0] +' End Date: '+ arr[1]);
        eventFormDate.range = arr;
    });
}

function showSelectedPeriodic(selected) {
    if (Array.isArray(selected)) {
        var selectedView = [];
        for (i = 0; i < selected.length; ++i) {
            if (selected[i].substr(0, 1) !== '0') {
                selectedView[i] = ' Every ';
                selectedView[i] += selected[i].substr(0, 1);
                selectedView[i] += ' week';
                selectedView[i] += ' ' + selected[i].slice(1) + '';
            } else {
                selectedView[i] = ' ' + selected[i].slice(1) + '';
            }
        }
        $('.periodic-hide').html('Periodic Dates: ' + selectedView);
    } else {
        $('.periodic-hide').html('Periodic Dates: none')
    }
}

function showMultipleDates(selected) {
    var multipleDatesArrView = [];
    if (Array.isArray(selected)) {
        selected.forEach(function (item, i, selected) {
            multipleDatesArrView[i] = ' ' + item;
        });
        $('.date-multiple-hide').html('Multiple Dates: ' + multipleDatesArrView);
    } else {
        $('.date-multiple-hide').html('Multiple Dates: none');
    }
}

function changeDateToggleAndResetFields(periodicField, startDateField, endDateField) {

    $('#date-range-close').click(function () {
        eventFormDate.range = '';
        $('.date-hide').html('Dates Range: none');
        emptyDate(startDateField, endDateField);
    });
    $('#periodic-close').click(function () {
        eventFormDate.periodic = '';
        $('.periodic-hide').html('Periodic Dates: none');
    });
    $('#date-multiple-close').click(function () {
        eventFormDate.multiple = '';
        $('.date-multiple-hide').html('Custom Dates: none');
    });

    $('#date-toggle').change(function() {
        if ($(this).prop('checked') === true) {
            $('.select-date').removeClass('hidden');
        } else {
            $('.select-date').addClass('hidden');
            $(periodicField).val('');
            emptyDate(startDateField, endDateField);
            eventFormDate = {
                periodic: '',
                range: '',
                multiple: ''
            };
            $('.periodic-hide').html('Periodic Dates: none');
            $('.date-hide').html('Dates Range: none');
            $('.date-multiple-hide').html('Multiple Dates: none');
        }
    });
}

function preSubmit(formName, periodicField, startDateField, endDateField) {
    $("* [name='"+formName+"']").submit(function() {

    if (Array.isArray(eventFormDate.periodic) && Array.isArray(eventFormDate.multiple)) {
        $(periodicField).val(eventFormDate.periodic.concat(eventFormDate.multiple));
    } else if (Array.isArray(eventFormDate.periodic)) {
        $(periodicField).val(eventFormDate.periodic);
    } else if (Array.isArray(eventFormDate.multiple)) {
        $(periodicField).val(eventFormDate.multiple);
    }

    if (Array.isArray(eventFormDate.range)) {
        var startDate = eventFormDate.range[0].split('/');
        var endDate = eventFormDate.range[1].split('/');

        $(startDateField + '_year').val(startDate[2]);
        $(startDateField + '_month').val(removeZero(startDate[0]));
        $(startDateField + '_day').val(removeZero(startDate[1]));
        $(endDateField+'_year').val(endDate[2]);
        $(endDateField + '_month').val(removeZero(endDate[0]));
        $(endDateField + '_day').val(removeZero(endDate[1]));
    }

    });
}

function removeZero (string) {
    if (string.substr(0, 1) === '0') {
        string = string.slice(1);
    }
    return string
}

function eventDateEdit(periodicField, startDateField, endDateField) {
    var eventStart = $(startDateField).children('select');
    var eventEnd = $(endDateField).children('select');
    if (eventStart.val() && eventEnd.val()) {
        eventStart[0].value;
        eventStart[1].value;
        eventStart[2].value;

        eventEnd[0].value;
        eventEnd[1].value;
        eventEnd[2].value;

        $('.date-hide').html('Dates Range: Start Date:' + eventStart[1].value+'/'+eventStart[2].value+'/'+eventStart[0].value+' End Date:'+eventEnd[1].value+'/'+eventEnd[2].value+'/'+eventEnd[0].value);
    }
    console.log(eventFormDate);
    showSelectedPeriodic(eventFormDate.periodic);
    showMultipleDates(eventFormDate.multiple);
}

// function checkClassActive(status, el, el2) {
//     if (status === 'inactive') {
//         $('#periodic-date-button').removeClass('active');
//         $('#custom-range-button').removeClass('active');
//     } else {
//         addOrRemoveClassActive(el, el2);
//     }
// }
//
// function addOrRemoveClassActive(el, el2) {
//     if($(el).hasClass('active')){
//         $(el).removeClass('active');
//     }else{
//         $(el).addClass('active');
//     }
//     $(el2).removeClass('active');
// }
//
function emptyDate (startDateField, endDateField) {
    $(startDateField + '_year').val('');
    $(startDateField + '_month').val('');
    $(startDateField + '_day').val('');
    $(endDateField+'_year').val('');
    $(endDateField + '_month').val('');
    $(endDateField + '_day').val('');
}