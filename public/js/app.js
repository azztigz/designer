"use strict";
var apps, imgLoaded;

apps = new Marionette.Application;

apps.addRegions({
  rightRegion: '#content-template',
  sidebar: '#sidebar_menu',
  catlists: '#catlists',
  preview: '#preview_wrap',
  pagination: '#content-pagination'
});

apps.Lists = Backbone.Model.extend();

apps.ListsCollection = Backbone.Collection.extend({
  model: apps.Lists
});

apps.lists = new apps.ListsCollection([
  {
    'id': '1',
    'name': 'Recent',
    'icon': '<i class="fa fa-folder-open-o"></i>'
  }, {
    'id': '2',
    'name': 'Works',
    'icon': '<i class="fa fa-folder-o"></i>'
  }, {
    'id': '3',
    'name': 'Templates',
    'icon': '<i class="fa fa-list"></i>'
  }
]);

apps.module('App', function(App, apps, Backbone, Marionette, $, _) {
  App.currentpage = '';
  App.temp = 'Templates';
  App.page = 'Recent';
  App.link = $('#link').attr('value');
  App.getInfo = function(col, vw, ur, reg, id) {
    col = new apps.ListsCollection();
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
      error: function() {}
    });
  };
  App.getInfoOne = function(col, vw, ur, reg, id, page) {
    col = new apps.Lists();
    return col.fetch({
      url: ur,
      data: {
        id: id,
        page: page
      },
      success: function() {
        var layout;
        layout = new vw({
          collection: col
        });
        return reg.show(layout);
      },
      error: function() {}
    });
  };
  App.Route = Backbone.Marionette.AppRouter.extend({
    routes: {
      '': 'view',
      ':page': 'view',
      ':page/:id': 'goTo',
      ':page/:slug/:id': 'dispTemp'
    },
    view: function(page, id) {
      $('.editor_overlay').css('display', 'block');
      $('#content-pagination').empty();
      if (page === null) {
        page = App.page;
      }
      App.currentpage = page;
      if (page !== "Templates") {
        if (page === "Works") {
          return App.getInfoOne('works', App.contentView, '/editor/works?link=' + App.link, apps.rightRegion, id);
        } else {
          return App.getInfoOne('recent', App.contentView, '/editor/works?link=' + App.link, apps.rightRegion, 'recent');
        }
      } else {
        return App.getInfoOne('temp', App.dispTempView, '/editor/categories?link=' + App.link, apps.rightRegion, 'all');
      }
    },
    dispTemp: function(page, slug, id) {
      $('#cat_id').attr('value', id);
      $('.editor_overlay').css('display', 'block');
      App.currentpage = page;
      return App.getInfoOne('temps', App.dispTempView, '/editor/categories?link=' + App.link, apps.rightRegion, id);
    },
    goTo: function(e) {
      var clickedEl, id;
      clickedEl = $(e.currentTarget);
      id = clickedEl.attr('id');
      return alert(id);
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
    checkTemp: function(id) {
      if (id === App.temp) {
        App.getInfo('cat', App.categories, '/editor/categories?link=' + App.link, apps.catlists);
        return $("#collapseExample").slideToggle('slow');
      } else {
        return $("#collapseExample").hide();
      }
    },
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
      this.checkTemp(id);
      return this.router.navigate(id, true);
    }
  });
  App.contentView = Marionette.LayoutView.extend({
    template: '#cont_template',
    onShow: function() {
      var pages;
      imgLoaded('content_temp');
      pages = this.collection.attributes.pages;
      $('#pages').attr('value', pages);
      return $('.editor_overlay').css('display', 'none');
    },
    templateHelpers: function() {
      return {
        link: function() {
          return "?link=" + App.link;
        }
      };
    },
    events: {
      "click #content_temp img": "checkImg"
    },
    checkImg: function(e) {
      var clickedEl, id, layout, temp;
      clickedEl = $(e.currentTarget);
      id = clickedEl.attr('id');
      temp = new apps.Lists({
        id: id,
        type: 'mWork'
      });
      layout = new App.previewTemp({
        model: temp
      });
      return apps.preview.show(layout);
    }
  });
  App.categories = Marionette.ItemView.extend({
    template: '#categories',
    events: {
      "click #cat_lists a": "checkCat"
    },
    checkCat: function(e) {
      var clickedEl, id;
      clickedEl = $(e.currentTarget);
      id = clickedEl.attr('id');
      $('#cat_lists a').each(function() {
        return $('#' + this.id).removeClass('active');
      });
      return $('#' + id).addClass('active');
    }
  });
  App.dispTempView = Marionette.LayoutView.extend({
    template: '#temp_template',
    onShow: function() {
      var layout, p, pages;
      imgLoaded('content_temp');
      pages = this.collection.attributes.pages;
      $('.editor_overlay').css('display', 'none');
      $('#pages').attr('value', pages);
      if (pages !== 0) {
        p = new apps.Lists({
          page: pages
        });
        layout = new App.paginationView({
          model: p
        });
        return apps.pagination.show(layout);
      } else {
        return $('#content-pagination').empty();
      }
    },
    events: {
      "click #content_temp img": "checkImg"
    },
    templateHelpers: function() {
      return {
        link: function() {
          return "?link=" + App.link;
        }
      };
    },
    checkImg: function(e) {
      var clickedEl, id, layout, temp;
      clickedEl = $(e.currentTarget);
      id = clickedEl.attr('id');
      temp = new apps.Lists({
        id: id,
        type: 'mTemplate'
      });
      layout = new App.previewTemp({
        model: temp
      });
      return apps.preview.show(layout);
    }
  });
  App.previewTemp = Marionette.ItemView.extend({
    template: '#preview_template',
    onShow: function() {
      return $('.editor_overlay').fadeOut('slow');
    },
    templateHelpers: function() {
      return {
        link: function() {
          return "?link=" + App.link;
        }
      };
    },
    events: {
      "click #goTo ": "goTo"
    },
    goTo: function(e) {
      var copyTemp, cpTemp, id, img, link, type;
      link = App.link;
      id = $(e.currentTarget).attr('data-id');
      type = $(e.currentTarget).attr('data-type');
      img = $(e.currentTarget).attr('data-link');
      if (img === 'mTemplate') {
        copyTemp = Backbone.Model.extend({
          url: '/editor/copytemp?link=' + App.link
        });
        cpTemp = new copyTemp({
          tempid: id
        });
        return cpTemp.save(null, {
          success: function(model, data) {
            if (data.status === 0) {
              return window.location = "editor/svgeditor?page=" + img + "&id=" + data.id + "&type=" + type + "&link=" + link;
            }
          },
          error: function(model, response) {
            return console.log(response.statusText);
          }
        });
      } else {
        return window.location = "editor/svgeditor?page=" + img + "&id=" + id + "&type=" + type + "&link=" + link;
      }
    }
  });
  App.copyTemp = function(id) {
    var copyTemp, cpTemp;
    copyTemp = Backbone.Model.extend({
      url: '/editor/copytemp?link=' + App.link
    });
    cpTemp = new copyTemp({
      tempid: id
    });
    return cpTemp.save(null, {
      success: function(model, data) {
        if (data.status === 0) {
          return data.id;
        }
      },
      error: function(model, response) {
        return console.log(response.statusText);
      }
    });
  };
  App.paginationView = Marionette.LayoutView.extend({
    template: '#pagination_template',
    initialize: function() {},
    events: {
      "click li a": "getPage"
    },
    getPage: function(e) {
      var cat_id, li, page;
      li = $(e.currentTarget).attr('id');
      $('#temp_pagination li').each(function() {
        return $('li').removeClass('active');
      });
      $('.pag' + li).addClass('active');
      $('.editor_overlay').css('display', 'block');
      page = $(e.currentTarget).attr('data-page');
      cat_id = $('#cat_id').val();
      return App.getInfoOne('page', App.tempPageView, '/editor/tempPage?link=' + App.link, apps.rightRegion, cat_id, page);
    }
  });
  App.paginationWorkView = Marionette.LayoutView.extend({
    template: '#pagination_template',
    initialize: function() {},
    events: {
      "click li a": "getPage"
    },
    getPage: function(e) {
      var cat_id, li, page;
      li = $(e.currentTarget).attr('id');
      $('#temp_pagination li').each(function() {
        return $('li').removeClass('active');
      });
      $('.pag' + li).addClass('active');
      $('.editor_overlay').css('display', 'block');
      page = $(e.currentTarget).attr('data-page');
      return cat_id = $('#cat_id').val();
    }
  });
  App.tempPageView = Marionette.LayoutView.extend({
    template: '#temp_template',
    onShow: function() {
      imgLoaded('content_temp');
      return $('.editor_overlay').css('display', 'none');
    },
    events: {
      "click #content_temp img": "checkPage"
    },
    templateHelpers: function() {
      return {
        link: function() {
          return "?link=" + App.link;
        }
      };
    },
    checkPage: function(e) {
      var clickedEl, id, layout, temp;
      clickedEl = $(e.currentTarget);
      id = clickedEl.attr('id');
      temp = new apps.Lists({
        id: id,
        type: 'mTemplate'
      });
      layout = new App.previewTemp({
        model: temp
      });
      return apps.preview.show(layout);
    }
  });
  return App.addInitializer(function() {
    var layout;
    layout = new App.defaultView({
      collection: apps.lists
    });
    return apps.sidebar.show(layout);
  });
});

imgLoaded = function(divwrap) {
  return $('#' + divwrap).imagesLoaded().always(function(instance) {}).progress(function(instance, image) {
    var $item, result;
    $item = $(image.img).parent();
    $item.removeClass('is-loading');
    return result = image.isLoaded ? 'loaded' : 'broken';
  });
};

//# sourceMappingURL=app.js.map