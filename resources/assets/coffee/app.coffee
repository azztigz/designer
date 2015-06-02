"use strict";

apps = new (Marionette.Application)

apps.addRegions
  rightRegion: '#content-template'
  sidebar: '#sidebar_menu'
  catlists: '#catlists'
  preview: '#preview_wrap'
  pagination: '#content-pagination'

apps.Lists = Backbone.Model.extend();

apps.ListsCollection = Backbone.Collection.extend(
  model: apps.Lists 
  # comparator: 'id'
)

apps.lists = new (apps.ListsCollection)([
  {
    'id': '1'
    'name': 'Recent'
    'icon': '<i class="fa fa-folder-open-o"></i>'
  }
  {
    'id': '2'
    'name': 'Works'
    'icon': '<i class="fa fa-folder-o"></i>'
  }
  {
    'id': '3'
    'name': 'Templates'
    'icon': '<i class="fa fa-list"></i>'
  }
])



apps.module 'App', (App, apps, Backbone, Marionette, $, _) ->

  App.currentpage = ''
  App.temp = 'Templates'
  App.page = 'Recent'
  App.link = $('#link').attr('value')

  App.getInfo = (col, vw, ur, reg, id) ->
    col = new (apps.ListsCollection)()
    col.fetch
      url: ur
      data:
        id:id
      success: ->
        layout = new (vw)(
          collection: col
        )
        reg.show layout
      error: ->
        # alert 'You are not authorized to access this site!!!'
        # window.location = '/'

  App.getInfoOne = (col, vw, ur, reg, id, page) ->
    col = new (apps.Lists)()
    col.fetch
      url: ur
      data:
        id:id
        page:page
      success: ->
        layout = new (vw)(
          collection: col
        )
        reg.show layout
      error: ->
        # alert 'You are not authorized to access this site!!!'
        # window.location = '/'

  App.Route = Backbone.Marionette.AppRouter.extend
    routes:
      '': 'view'
      ':page': 'view'
      ':page/:id' : 'goTo'
      ':page/:slug/:id': 'dispTemp'
    view: (page, id) ->
      $('.editor_overlay').css('display','block')
      $('#content-pagination').empty()
      if page == null
        page = App.page
      App.currentpage = page;

      if page != "Templates"
        if page == "Works"
          App.getInfoOne 'works', App.contentView, '/editor/works?link='+App.link, apps.rightRegion, id
        else
          App.getInfoOne 'recent', App.contentView, '/editor/works?link='+App.link, apps.rightRegion, 'recent'
      else
        App.getInfoOne 'temp', App.dispTempView, '/editor/categories?link='+App.link, apps.rightRegion, 'all'

    dispTemp: (page, slug, id) ->
      $('#cat_id').attr 'value', id
      $('.editor_overlay').css('display','block')
      App.currentpage = page;
      App.getInfoOne 'temps', App.dispTempView, '/editor/categories?link='+App.link, apps.rightRegion, id
    goTo: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')

      alert id

  App.viewInfo = Marionette.ItemView.extend
    template: '#info-view'

  App.defaultView = Marionette.LayoutView.extend
    onAttach: ->
      $('.' + App.currentpage).addClass 'active'
    initialize: ->
      @router = new (App.Route)
      if !Backbone.History.started
        Backbone.history.start()
    template: '#menu_template'
    checkTemp: (id) -> 
      if id == App.temp
        App.getInfo 'cat', App.categories, '/editor/categories?link='+App.link, apps.catlists

        $("#collapseExample").slideToggle 'slow'
      else
        $("#collapseExample").hide()
    events:
      'click a': 'checkA'
    checkA: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')

      $('#sidebar_menu a').each ->
       $('#' + @id).removeClass 'active'

      $('#' + id).addClass 'active'
      this.checkTemp(id)
      @router.navigate id, true


  App.contentView = Marionette.LayoutView.extend
    template: '#cont_template'
    onShow: ->
      imgLoaded 'content_temp'
      pages = this.collection.attributes.pages
      $('#pages').attr 'value', pages
      
      $('.editor_overlay').css('display','none')
      
    templateHelpers: ->
      { 
        link: ->
          "?link="+App.link
      }
    events:
      "click #content_temp img": "checkImg"
    checkImg: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')

      temp = new apps.Lists({id:id, type:'mWork'});

      layout = new App.previewTemp
        model:temp
      
      apps.preview.show layout

  App.categories = Marionette.ItemView.extend
    template: '#categories'
    events:
      "click #cat_lists a": "checkCat"
    checkCat: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')
      $('#cat_lists a').each ->
       $('#' + @id).removeClass 'active'

      $('#' + id).addClass 'active'


  App.dispTempView = Marionette.LayoutView.extend
    template: '#temp_template'
    onShow: ->
      imgLoaded 'content_temp'
      pages = this.collection.attributes.pages
     
      $('.editor_overlay').css('display','none')
      $('#pages').attr 'value', pages
      if pages != 0
        p = new apps.Lists({
            page: pages
          })
        layout = new App.paginationView
          model: p
        apps.pagination.show layout
      else
        $('#content-pagination').empty()

      
    events:
      "click #content_temp img": "checkImg"
    templateHelpers: ->
      { 
        link: ->
          "?link="+App.link
      }
    checkImg: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')

      temp = new apps.Lists({id:id, type:'mTemplate'});

      layout = new App.previewTemp
        model:temp
      
      apps.preview.show layout

  App.previewTemp = Marionette.ItemView.extend
    template: '#preview_template'
    onShow: ->
      $('.editor_overlay').fadeOut 'slow'
    templateHelpers: ->
      { 
        link: ->
          "?link="+App.link
      }
    events:
      "click #goTo ":"goTo"
    goTo: (e) ->
      link = App.link
      id = $(e.currentTarget).attr 'data-id'
      type = $(e.currentTarget).attr 'data-type'
      img = $(e.currentTarget).attr 'data-link'

      if img == 'mTemplate'
        copyTemp = Backbone.Model.extend(
          url: '/editor/copytemp?link='+App.link
        )
        cpTemp = new copyTemp(
          tempid: id
        )
        cpTemp.save null,
          success: (model, data) ->
            if data.status == 0
              window.location = "editor/svgeditor?page="+img+"&id="+data.id+"&type="+type+"&link="+link
          error: (model, response) ->
            console.log response.statusText
        # window.location = "editor/svgeditor?page="+img+"&id="+id+"&type="+type+"&link="+link
      else
        window.location = "editor/svgeditor?page="+img+"&id="+id+"&type="+type+"&link="+link
  App.copyTemp = (id) ->
    copyTemp = Backbone.Model.extend(
      url: '/editor/copytemp?link='+App.link
    )
    cpTemp = new copyTemp(
      tempid: id
    )

    cpTemp.save null,
      success: (model, data) ->
        if data.status == 0
          data.id
      error: (model, response) ->
        console.log response.statusText

  App.paginationView = Marionette.LayoutView.extend
    template: '#pagination_template'
    initialize: ->
      # console.log this.model
    events:
      "click li a":"getPage"
    getPage: (e)->
      li = $(e.currentTarget).attr('id')
      $('#temp_pagination li').each ->
       $('li').removeClass 'active'

      $('.pag' + li).addClass 'active'

      $('.editor_overlay').css('display','block')
      page = $(e.currentTarget).attr('data-page')
      cat_id = $('#cat_id').val()
      App.getInfoOne 'page', App.tempPageView, '/editor/tempPage?link='+App.link, apps.rightRegion, cat_id, page

  App.paginationWorkView = Marionette.LayoutView.extend
    template: '#pagination_template'
    initialize: ->
      # console.log this.model
    events:
      "click li a":"getPage"
    getPage: (e)->
      li = $(e.currentTarget).attr('id')
      $('#temp_pagination li').each ->
       $('li').removeClass 'active'

      $('.pag' + li).addClass 'active'

      $('.editor_overlay').css('display','block')
      page = $(e.currentTarget).attr('data-page')
      cat_id = $('#cat_id').val()
      
      # App.getInfoOne 'page', App.tempPageView, '/editor/workPage?link='+App.link, apps.rightRegion, cat_id, page

  App.tempPageView = Marionette.LayoutView.extend
    template: '#temp_template'
    onShow: ->
      imgLoaded 'content_temp'
      $('.editor_overlay').css('display','none')
    events:
      "click #content_temp img": "checkPage"
    templateHelpers: ->
      { 
        link: ->
          "?link="+App.link
      }
    checkPage: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')

      temp = new apps.Lists({id:id, type:'mTemplate'});

      layout = new App.previewTemp
        model:temp
      
      apps.preview.show layout

  App.addInitializer ->
    layout = new (App.defaultView)(collection: apps.lists)
    apps.sidebar.show layout 


imgLoaded = (divwrap) ->
  $('#'+divwrap).imagesLoaded().always((instance) ->
    #   console.log 'all images loaded'
    # ).done((instance) ->
    #   console.log 'all images successfully loaded'
    # ).fail(->
    #   console.log 'all images loaded, at least one is broken'
    ).progress (instance, image) ->
      $item = $( image.img ).parent();
      $item.removeClass('is-loading');
      result = if image.isLoaded then 'loaded' else 'broken'
      # console.log 'image is ' + result + ' for ' + image.img.src
