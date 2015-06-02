"use strict";
var apps;

apps = new Marionette.Application;

apps.addRegions({
  leftRegion: '#left-sidebar',
  rightRegion: '#right-sidebar'
});

apps.Lists = Backbone.Model.extend();

apps.ListsCollection = Backbone.Collection.extend({
  model: apps.Lists,
  comparator: 'id'
});

apps.lists = new apps.ListsCollection([
  {
    'id': '1',
    'name': 'Recent'
  }, {
    'id': '2',
    'name': 'Works'
  }, {
    'id': '3',
    'name': 'Templates'
  }
]);

apps.module('App', function(App, apps, Backbone, Marionette, $, _) {
  'use strict';
  App.currentpage = '';
  App.Route = Backbone.Marionette.AppRouter.extend({
    routes: {
      '': 'view',
      ':page': 'view',
      ':page/view/:id': 'viewInfo',
      ':page/edit/:id': 'editInfo'
    },
    view: function(page, id) {
      if (page === null) {
        page = 'Offset';
      }
      App.currentpage = page;
      return alert('test');
    },
    viewInfo: function(page, id) {
      App.currentpage = page;
      return alert('test');
    }
  });
  App.currentList = Marionette.ItemView.extend({
    template: '#menu_template'
  });
  App.viewInfo = Marionette.ItemView.extend({
    template: '#info-view'
  });
  App.defaultView = Marionette.ItemView.extend({
    initialize: function() {
      var layout;
      layout = new App.contentView;
      apps.rightRegion.show(layout);
      this.router = new App.Route();
      if( ! Backbone.History.started) Backbone.history.start();
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
        $('#' + this.id).removeClass('active');
        return $('#' + id).addClass('active');
      });
      return this.router.navigate(id, true);
    }
  });
  App.contentView = Marionette.ItemView.extend({
    template: '#content_template'
  });
  return App.addInitializer(function() {
    var layout;
    layout = new App.defaultView({
      collection: apps.lists
    });
    return apps.leftRegion.show(layout);
  });
});

//# sourceMappingURL=app.js.map