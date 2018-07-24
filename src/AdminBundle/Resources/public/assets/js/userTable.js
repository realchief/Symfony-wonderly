var App = (function () {

    App.dataTables = function( ){};

    App.initializeDataTable = function (selector, options) {
        var table = $(selector);
        var actionsObj = table.data('actionObj');
        var opt = $.extend({
            processing: true,
            serverSide: true
        }, options);
        opt.columns = table
            .find('th[data-property]')
            .map(function () {
                var property = this.dataset.property;
                var sortName = this.dataset.sortName;
                if (property == 'roles') {
                    return {
                        data: "roles",
                        render: function (data, type, row) {
                            return data;
                        }
                    };
                }
                if (property == 'action') {
                    return {
                        data: "action",
                        render: function (data, type, row) {
                            return '';
                        }
                    };
                }
                if (actionsObj && window[actionsObj] && window[actionsObj][property]) {
                    property = window[actionsObj][property];
                }
                return {
                    data: property,
                    orderable: !!sortName,
                    name: sortName
                };
            })
            .get();
        return table.DataTable(opt);
    };
    App.createSerializeFormFn = function (searchForm) {
        if (typeof searchForm === 'string') {
            searchForm = $(searchForm);
        }
        return function (data) {
            var rawData = searchForm.serializeArray();
            var normalizedData = {};
            // Convert to object.
            for (var idx in rawData) {
                if (rawData.hasOwnProperty(idx)) {
                    normalizedData[rawData[idx].name] = rawData[idx].value;
                }
            }
            data.filter = normalizedData;
        }
    };
    return App;
})(App || {});