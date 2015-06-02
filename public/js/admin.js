"use strict";
var admins, createTemp, newTemp, saveTemp;

admins = new Marionette.Application;

admins.addRegions({
  rightRegion: '#content_wrapper',
  sidebar: '#sidebar_menu',
  newTempwrapper: '#newTemp_wrapper',
  editTempwrapper: '#editTemp_wrapper',
  editButwrapper: '#editBut_wrapper',
  catLists: '#catLists'
});

admins.Lists = Backbone.Model.extend();

admins.ListsCollection = Backbone.Collection.extend({
  model: admins.Lists
});

admins.lists = new admins.ListsCollection([
  {
    'id': '1',
    'name': 'Templates',
    'icon': '<i class="fa fa-list"></i>'
  }, {
    'id': '2',
    'name': 'Categories',
    'icon': '<i class="fa fa-list-alt"></i>'
  }, {
    'id': '3',
    'name': 'Users',
    'icon': '<i class="fa fa-users"></i>'
  }, {
    'id': '4',
    'name': 'Fotolia',
    'icon': '<i class="fa fa-image"></i>'
  }, {
    'id': '5',
    'name': 'Library',
    'icon': '<i class="fa fa-th"></i>'
  }, {
    'id': '6',
    'name': 'Configurations',
    'icon': '<i class="fa fa-wrench"></i>'
  }
]);

admins.module('App', function(App, admins, Backbone, Marionette, $, _) {
  App.currentpage = '';
  App.page = 'Templates';
  App.getInfo = function(col, vw, ur, reg, id) {
    col = new admins.ListsCollection();
    return col.fetch({
      url: ur,
      data: {
        id: id
      },
      success: function() {
        var layout;
        layout = new vw({
          collection: col
        });
        return reg.show(layout);
      },
      error: function() {
        return console.log('No result found!!!');
      }
    });
  };
  App.getInfoOne = function(col, vw, ur, reg, id) {
    col = new admins.Lists();
    return col.fetch({
      url: ur,
      data: {
        id: id
      },
      success: function() {
        var layout;
        layout = new vw({
          model: col
        });
        return reg.show(layout);
      },
      error: function() {
        return console.log('No result found!!!');
      }
    });
  };
  App.delTemp = function(id) {
    var delTemp, deleteTemp;
    deleteTemp = Backbone.Model.extend({
      url: '/admin/delTemp'
    });
    delTemp = new deleteTemp({
      tempid: id
    });
    return delTemp.save(null, {
      success: function(model, data) {
        if (data.status === 0) {
          return $("tr#" + id).remove();
        }
      },
      error: function(model, response) {
        return console.log(response.statusText);
      }
    });
  };
  App.Route = Backbone.Marionette.AppRouter.extend({
    routes: {
      '': 'view',
      ':page': 'view',
      ':page/edit/:slug': 'edit',
      ':page/del/:id': 'Del',
      ':page/new': 'new'
    },
    view: function(page, id) {
      $('#new_template_form').hide();
      $('#edit_template_form').hide();
      if (page === null) {
        page = App.page;
      }
      App.currentpage = page;
      $('#editor_menu').html(page);
      App.page = page;
      switch (page) {
        case "Templates":
          return App.getInfo('temp', App.templateView, '/admin/templates', admins.rightRegion);
        case "Categories":
          return App.getInfo('cat', App.categoryView, '/admin/categories', admins.rightRegion);
      }
    },
    "new": function(page) {
      var layout;
      App.currentpage = page;
      $('#editor_menu').html(page);
      $('#content_wrapper').empty();
      switch (page) {
        case "Templates":
          $('#new_template_form').fadeIn('slow');
          layout = new App.TempNewView;
          return admins.newTempwrapper.show(layout);
        case "Categories":
          App.currentpage = page;
          layout = new App.CatNewView;
          return admins.rightRegion.show(layout);
      }
    },
    edit: function(page, slug) {
      $('#editor_menu').html(page);
      $('#content_wrapper').empty();
      $('#edit_template_form').fadeIn('slow');
      App.currentpage = page;
      return App.getInfoOne('temp', App.TempEditView, '/admin/templates/edit/' + slug, admins.editTempwrapper);
    }
  });
  App.viewInfo = Marionette.ItemView.extend({
    template: '#info-view'
  });
  App.defaultView = Marionette.LayoutView.extend({
    onAttach: function() {
      return $('.' + App.currentpage).addClass('active');
    },
    initialize: function() {
      this.router = new App.Route;
      if (!Backbone.History.started) {
        return Backbone.history.start();
      }
    },
    template: '#menu_template',
    events: {
      'click a': 'checkA'
    },
    checkA: function(e) {
      var clickedEl, id;
      clickedEl = $(e.currentTarget);
      id = clickedEl.attr('id');
      $('#sidebar_menu a').each(function() {
        return $('#' + this.id).removeClass('active');
      });
      $('#' + id).addClass('active');
      $('#editor_menu').html(id);
      return this.router.navigate(id, true);
    }
  });
  App.templateView = Marionette.ItemView.extend({
    initialize: function() {
      return $('#new_template_form').hide();
    },
    template: '#temp_template',
    templateHelpers: function() {
      return {
        page: function() {
          return App.page;
        }
      };
    },
    events: {
      "click #temp_view button": "View",
      "click #tempEdit": "Edit",
      "click #tempDel": "Del",
      "click #newTemp": "newTemp"
    },
    View: function(e) {
      var image;
      image = $(e.currentTarget).attr('data-image');
      return $('#tempImg').attr('src', image);
    },
    Del: function(e) {
      var id;
      id = $(e.currentTarget).attr('data-id');
      return App.delTemp(id);
    }
  });
  App.TempNewView = Marionette.ItemView.extend({
    template: '#newTemp_template',
    initialize: function() {
      return App.getInfo('cat', App.CatListsView, '/admin/categories', admins.catLists);
    },
    events: {
      'click #newCancel': 'backTo',
      'click #saveTemp': 'saveTemp'
    },
    backTo: function() {
      $('#new_template_form').hide();
      return window.location = '#Templates';
    },
    saveTemp: function() {
      return createTemp();
    }
  });
  App.CatListsView = Marionette.ItemView.extend({
    template: '#catLists_template',
    templateHelpers: function() {
      return {
        state: function() {
          return "select-state";
        },
        wrap: function() {
          return "wrapper";
        }
      };
    },
    onShow: function() {
      return newTemp('select-state', 'wrapper', 'categories');
    }
  });
  App.TempEditView = Marionette.LayoutView.extend({
    template: '#editTemp_template',
    regions: {
      editCatlists: '#editCatLists'
    },
    initialize: function() {
      var layout;
      layout = new App.editButView;
      return admins.editButwrapper.show(layout);
    },
    onShow: function() {
      return newTemp('editselect-state', 'editwrapper', 'editcategories');
    }
  });
  App.editButView = Marionette.ItemView.extend({
    template: '#editButTemp_template',
    events: {
      'click #editCancel': 'backTo',
      'click #editSave': 'saveTemp'
    },
    backTo: function() {
      $('#edit_template_form').hide();
      return window.location = '#Templates';
    },
    saveTemp: function() {
      return saveTemp();
    }
  });
  App.editCatListsView = Marionette.ItemView.extend({
    template: '#catLists_template',
    templateHelpers: function() {
      return {
        state: function() {
          return "editselect-state";
        },
        wrap: function() {
          return "editwrapper";
        }
      };
    },
    onShow: function() {
      return newTemp('editselect-state', 'editwrapper', 'editcategories');
    }
  });
  App.categoryView = Marionette.ItemView.extend({
    template: '#cat_template',
    templateHelpers: function() {
      return {
        page: function() {
          return App.page;
        }
      };
    },
    events: {
      "click #temp_view button": "View",
      "click #catEdit": "Edit",
      "click #catDel": "Del"
    },
    View: function(e) {
      var id;
      id = $(e.currentTarget).attr('id');
      return alert(id);
    },
    Edit: function(e) {
      var id;
      id = $(e.currentTarget).attr('data-id');
      return alert(id);
    },
    Del: function(e) {
      var id;
      id = $(e.currentTarget).attr('data-id');
      return alert(id);
    }
  });
  App.CatNewView = Marionette.ItemView.extend({
    template: '#newCat_template',
    initialize: function() {
      return App.getInfo('cat', App.CatListsView, '/admin/categories', admins.catLists);
    },
    events: {
      'click #newCat': 'backTo',
      'click #saveCat': 'saveCat'
    },
    backTo: function() {
      $('#new_category_form').hide();
      return window.location = '#Categories';
    },
    saveTemp: function() {}
  });
  App.categories = Marionette.ItemView.extend({
    template: '#categories'
  });
  App.dispTempView = Marionette.ItemView.extend({
    template: '#temp_template'
  });
  return App.addInitializer(function() {
    var layout;
    layout = new App.defaultView({
      collection: admins.lists
    });
    return admins.sidebar.show(layout);
  });
});

createTemp = function() {
  var newTemp, saveTemp;
  newTemp = Backbone.Model.extend({
    url: '/admin/newTemp'
  });
  saveTemp = new newTemp({
    temp_name: $('#name').val(),
    temp_desc: $('#desc').val(),
    slug: $('#slug').val(),
    categories: $('#categories').val(),
    back: $('#backsvg').val(),
    front: $('#frontsvg').val(),
    backjpg: $('#backjpg').val(),
    frontjpg: $('#frontjpg').val(),
    frontFolder: $('#frontsvgFolder').attr('value'),
    backFolder: $('#backsvgFolder').attr('value')
  });
  return saveTemp.save(null, {
    success: function(model, data) {
      var selectize;
      if (data.status === 0) {
        $('#newTemplate').each(function() {
          return this.reset();
        });
        selectize = $("#select-state")[0].selectize;
        selectize.clear();
        $('.dz-success').empty();
        $('.dz-message').show();
        $('#errors').empty().hide();
        return window.location = '#Templates';
      } else {
        $('#errors').empty();
        _.each(data.errors, function(item) {
          return $('#errors').append('<p>' + item + '</p>');
        });
        return $('#errors').fadeIn('slow');
      }
    },
    error: function(model, response) {
      return console.log(response.statusText);
    }
  });
};

saveTemp = function() {
  var upTemp, updateTemp;
  updateTemp = Backbone.Model.extend({
    url: '/admin/updateTemp'
  });
  upTemp = new updateTemp({
    tempid: $('#tempid').val(),
    temp_name: $('#editname').val(),
    temp_desc: $('#editdesc').val(),
    categories: $('#editcategories').val(),
    back: $('#editbacksvg').val(),
    front: $('#editfrontsvg').val(),
    backjpg: $('#editbackjpg').val(),
    frontjpg: $('#editfrontjpg').val()
  });
  return upTemp.save(null, {
    success: function(model, data) {
      var selectize;
      if (data.status === 0) {
        $('#editTemplate').each(function() {
          return this.reset();
        });
        selectize = $("#editselect-state")[0].selectize;
        selectize.clear();
        $('.dz-success').empty();
        $('.dz-message').show();
        $('#errors').empty().hide();
        return window.location = '#Templates';
      } else {
        $('#editerrors').empty();
        _.each(data.errors, function(item) {
          return $('#editerrors').append('<p>' + item + '</p>');
        });
        return $('#editerrors').fadeIn('slow');
      }
    },
    error: function(model, response) {
      return console.log(response.statusText);
    }
  });
};

newTemp = function(state, wrap, cat) {
  $('#' + state).selectize({
    plugins: ['remove_button'],
    maxItems: null
  });
  return $(function() {
    var $wrapper;
    $wrapper = $('#' + wrap);
    return $('select.selectized,input.selectized', $wrapper).each(function() {
      var $input, update;
      $input = $(this);
      update = function(e) {
        if ($input.val()) {
          return $('#' + cat).val(JSON.stringify($input.val()));
        } else {
          return $('#' + cat).val('');
        }
      };
      $(this).on('change', update);
      return update();
    });
  });
};

Dropzone.options.temp = {
  maxFiles: 1,
  acceptedFiles: 'image/svg+xml',
  dictDefaultMessage: 'Drop image here',
  maxFilesize: 100,
  createImageThumbnails: true,
  thumbnailWidth: '400',
  thumbnailHeight: '300',
  addRemoveLinks: true,
  dictInvalidFileType: 'Invalid file!!!',
  accept: function(file, done) {
    return done();
  },
  init: function() {
    this.on('maxfilesexceeded', function(file) {
      this.removeAllFiles();
      return this.addFile(file);
    });
    this.on('error', function(file) {
      return this.removeFile(file);
    });
    this.on('success', function(file, response) {
      $('#' + response.type + 'svg').attr('value', response.filename);
      $('#' + response.type + 'jpg').attr('value', response.jpg);
      $('#' + response.type + 'svgFolder').attr('value', response.folder);
      return $('#' + response.type + 'svgFolder').attr('value', response.folder);
    });
    return this.on('removedfile', function() {});
  }
};

//# sourceMappingURL=admin.js.map