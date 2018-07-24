var App = (function () {

  App.dataTables = function( ){};

  App.initializeDataTable = function (selector, options) {
      var table = $(selector);
      var actionsObj = table.data('actionObj');
      var opt = $.extend({
          processing: true,
          serverSide: true
      }, options);
      if (table.selector == '#admin_event_table') {
          opt.columns = table
              .find('th[data-property]')
              .map(function () {
                  var property = this.dataset.property;
                  var sortName = this.dataset.sortName;
                  if (property == 'action') {
                      return {
                          data: "action",
                          render: function (data, type, row) {
                              var render = '<a href="' + data.img + '" class="btn btn-primary">Img</a>&nbsp;<a href="' + data.edit + '" class="btn btn-primary">Edit</a>';
                              return render;
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
      } else if (table.selector == '#admin_organize_table') {
          opt.columns = table
              .find('th[data-property]')
              .map(function () {
                  var property = this.dataset.property;
                  var sortName = this.dataset.sortName;
                  if (property == 'img') {
                      return {
                          data: property,
                          render: function (data, type, row) {
                              console.log(property);
                              return '<img alt="Null" src="/uploads/image/'+ data +'" width="100" id="profile-image1" class="img-thumbnail img-responsive">';
                          },
                          orderable: !!sortName,
                          name: sortName
                      };
                  }
                  if (property == 'action') {
                      return {
                          data: property,
                          render: function (data, type, row) {
                              console.log(data);
                              var render =  '<a href="' + data.edit + '" class="btn btn-primary btn-xs">Edit</a>'+
                                  '<br>' +
                                  // '<a href="' + data.all_events + '" class="btn btn-primary btn-xs">All Events</a>' +
                                  // '<br>' +
                                  '<a href="' + data.add_event + '" class="btn btn-primary btn-xs">Add Event</a>';
                                  // '<br>' +
                                  // '<a href="' + data.delete_user + '" class="btn btn-primary btn-xs">Delete User</a>' +
                                  // '<br>' +
                                  // '<a href="' + data.delete_organizer + '" class="btn btn-primary btn-xs">Delete Organizer</a>';
                                  return render;
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
      } else if (table.selector == '#admin_user_table') {
          opt.columns = table
              .find('th[data-property]')
              .map(function () {
                  var property = this.dataset.property;
                  var sortName = this.dataset.sortName;
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
      }
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
