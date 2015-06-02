"use strict";

admins = new (Marionette.Application)

admins.addRegions
  rightRegion: '#content_wrapper'
  sidebar: '#sidebar_menu'
  newTempwrapper: '#newTemp_wrapper'
  editTempwrapper: '#editTemp_wrapper'
  editButwrapper: '#editBut_wrapper'
  catLists: '#catLists'

admins.Lists = Backbone.Model.extend();

admins.ListsCollection = Backbone.Collection.extend(
  model: admins.Lists 
  # comparator: 'id'
)

admins.lists = new (admins.ListsCollection)([
  {
    'id': '1'
    'name': 'Templates'
    'icon': '<i class="fa fa-list"></i>'
  }
  {
    'id': '2'
    'name': 'Categories'
    'icon': '<i class="fa fa-list-alt"></i>'
  }
  {
    'id': '3'
    'name': 'Users'
    'icon': '<i class="fa fa-users"></i>'
  }
   {
    'id': '4'
    'name': 'Fotolia'
    'icon': '<i class="fa fa-image"></i>'
  }
  {
    'id': '5'
    'name': 'Library'
    'icon': '<i class="fa fa-th"></i>'
  }
  {
    'id': '6'
    'name': 'Configurations'
    'icon': '<i class="fa fa-wrench"></i>'
  }
])


admins.module 'App', (App, admins, Backbone, Marionette, $, _) ->

  App.currentpage = ''
  App.page = 'Templates'

  App.getInfo = (col, vw, ur, reg, id) ->
    col = new (admins.ListsCollection)()
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
        console.log 'No result found!!!'

  App.getInfoOne = (col, vw, ur, reg, id) ->
    col = new (admins.Lists)()
    col.fetch
      url: ur
      data:
        id:id
      success: ->
        layout = new (vw)(
          model: col
        )
        reg.show layout
      error: ->
        console.log 'No result found!!!'

  App.delTemp = (id) ->
    deleteTemp = Backbone.Model.extend(
      url: '/admin/delTemp'
    )
    delTemp = new deleteTemp(
      tempid: id
    )

    delTemp.save null,
      success: (model, data) ->
        if data.status == 0
          $("tr#"+id).remove()
      error: (model, response) ->
        console.log response.statusText

  App.Route = Backbone.Marionette.AppRouter.extend
    routes:
      '': 'view'
      ':page': 'view'
      ':page/edit/:slug' : 'edit'
      ':page/del/:id' : 'Del'
      ':page/new' : 'new'

    view: (page, id) ->

      $('#new_template_form').hide()
      $('#edit_template_form').hide()

      if page == null
        page = App.page
      App.currentpage = page;

      $('#editor_menu').html page
      App.page = page
      switch page
      	when "Templates"
      		App.getInfo 'temp', App.templateView, '/admin/templates', admins.rightRegion
      	when "Categories"
      		App.getInfo 'cat', App.categoryView, '/admin/categories', admins.rightRegion
    
    new: (page)->
      App.currentpage = page;
      $('#editor_menu').html page
      $('#content_wrapper').empty()
      switch page
        when "Templates"
          $('#new_template_form').fadeIn('slow')
          layout = new App.TempNewView
          admins.newTempwrapper.show layout
        when "Categories"
          App.currentpage = page;
          layout = new App.CatNewView
          admins.rightRegion.show layout

    edit: (page, slug)->
      $('#editor_menu').html page
      $('#content_wrapper').empty()
      $('#edit_template_form').fadeIn('slow')
      App.currentpage = page;

      App.getInfoOne 'temp', App.TempEditView, '/admin/templates/edit/'+slug, admins.editTempwrapper

      # layout = new App.TempEditView
      # admins.editTempwrapper.show layout

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
    events:
      'click a': 'checkA'
    checkA: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')

      $('#sidebar_menu a').each ->
       $('#' + @id).removeClass 'active'

      $('#' + id).addClass 'active'
      $('#editor_menu').html id
      @router.navigate id, true

#Templates block ---------------------------------------------------------------------

  App.templateView = Marionette.ItemView.extend(
    initialize: -> 
      $('#new_template_form').hide()
    template: '#temp_template'
    templateHelpers: ->
      { page: ->
        App.page
    }
    events:
      "click #temp_view button":"View"
      "click #tempEdit":"Edit"
      "click #tempDel":"Del"
      "click #newTemp": "newTemp"
    View: (e) ->
      image = $(e.currentTarget).attr('data-image')
      $('#tempImg').attr 'src', image  
      
    Del: (e) ->
      id = $(e.currentTarget).attr('data-id')
      App.delTemp(id)
  )

  App.TempNewView = Marionette.ItemView.extend
    template: '#newTemp_template'
    initialize: ->
      App.getInfo 'cat', App.CatListsView, '/admin/categories', admins.catLists 
    events: 
      'click #newCancel': 'backTo'
      'click #saveTemp' : 'saveTemp'
    backTo: ->
      $('#new_template_form').hide()
      window.location = '#Templates'
    saveTemp: ->
      createTemp()

  App.CatListsView = Marionette.ItemView.extend
    template: '#catLists_template'
    templateHelpers: ->
      {
        state: ->
          "select-state"
        wrap: ->
          "wrapper"
      }
    onShow: ->
      newTemp 'select-state', 'wrapper', 'categories'

  App.TempEditView = Marionette.LayoutView.extend
    template: '#editTemp_template'
    regions:
      editCatlists:'#editCatLists'
    initialize: ->
       # App.getInfo 'cat', App.editCatListsView, '/admin/categories', @editCatlists 
       layout = new App.editButView
       admins.editButwrapper.show layout
    onShow: ->
      newTemp 'editselect-state', 'editwrapper', 'editcategories'
      

  App.editButView = Marionette.ItemView.extend
    template: '#editButTemp_template'
    events: 
      'click #editCancel': 'backTo'
      'click #editSave' : 'saveTemp'
    backTo: ->
      $('#edit_template_form').hide()
      window.location = '#Templates'
    saveTemp: ->
      saveTemp()

  App.editCatListsView = Marionette.ItemView.extend
    template: '#catLists_template'
    templateHelpers: ->
      {
        state: ->
          "editselect-state"
        wrap: ->
          "editwrapper"
      }
    onShow: ->
      newTemp 'editselect-state', 'editwrapper', 'editcategories'

#Categories block ---------------------------------------------------------------------

  App.categoryView = Marionette.ItemView.extend(
    template: '#cat_template'
    templateHelpers: ->
      { page: ->
        App.page
    }
    events:
      "click #temp_view button":"View"
      "click #catEdit":"Edit"
      "click #catDel":"Del"
    View: (e) ->
      id = $(e.currentTarget).attr('id')
      alert id
    Edit: (e) ->
      id = $(e.currentTarget).attr('data-id')
      alert id
    Del: (e) ->
      id = $(e.currentTarget).attr('data-id')
      alert id
  )

  App.CatNewView = Marionette.ItemView.extend
    template: '#newCat_template'
    initialize: ->
      App.getInfo 'cat', App.CatListsView, '/admin/categories', admins.catLists 
    events: 
      'click #newCat': 'backTo'
      'click #saveCat' : 'saveCat'
    backTo: ->
      $('#new_category_form').hide()
      window.location = '#Categories'
    saveTemp: ->
      # createCat()

  App.categories = Marionette.ItemView.extend
    template: '#categories'

  App.dispTempView = Marionette.ItemView.extend
    template: '#temp_template'


  App.addInitializer ->
    layout = new (App.defaultView)(collection: admins.lists)
    admins.sidebar.show layout 










# Create Template 

createTemp = ->
  newTemp = Backbone.Model.extend(
    url: '/admin/newTemp'
  )
  saveTemp = new newTemp(
    temp_name: $('#name').val()
    temp_desc: $('#desc').val()
    slug: $('#slug').val()
    categories: $('#categories').val()
    back: $('#backsvg').val()
    front: $('#frontsvg').val()
    backjpg: $('#backjpg').val()
    frontjpg: $('#frontjpg').val()
    frontFolder: $('#frontsvgFolder').attr('value')
    backFolder: $('#backsvgFolder').attr('value')
  )

  saveTemp.save null,
    success: (model, data) ->
      if data.status == 0
        $('#newTemplate').each ->
          @reset()
        selectize = $("#select-state")[0].selectize
        selectize.clear();
        $('.dz-success').empty();
        $('.dz-message').show();
        $('#errors').empty().hide()
        window.location = '#Templates'
      else
        $('#errors').empty()
        _.each data.errors, (item) ->
          $('#errors').append('<p>'+item+'</p>')
        $('#errors').fadeIn('slow')
    error: (model, response) ->
      console.log response.statusText

saveTemp = ->
  updateTemp = Backbone.Model.extend(
    url: '/admin/updateTemp'
  )
  upTemp = new updateTemp(
    tempid: $('#tempid').val()
    temp_name: $('#editname').val()
    temp_desc: $('#editdesc').val()
    # slug: $('#editslug').val()
    categories: $('#editcategories').val()
    back: $('#editbacksvg').val()
    front: $('#editfrontsvg').val()
    backjpg: $('#editbackjpg').val()
    frontjpg: $('#editfrontjpg').val()
  )

  upTemp.save null,
    success: (model, data) ->
      if data.status == 0
        $('#editTemplate').each ->
          @reset()
        selectize = $("#editselect-state")[0].selectize
        selectize.clear();
        $('.dz-success').empty();
        $('.dz-message').show();
        $('#errors').empty().hide()
        window.location = '#Templates'
      else
        $('#editerrors').empty()
        _.each data.errors, (item) ->
          $('#editerrors').append('<p>'+item+'</p>')
        $('#editerrors').fadeIn('slow')
    error: (model, response) ->
      console.log response.statusText


#Categories

newTemp = (state,wrap,cat)-> 
  $('#'+state).selectize
    plugins: [ 'remove_button' ]
    maxItems: null
    # onDelete: (values) ->
    #   confirm if values.length > 1 then 'Are you sure you want to remove these ' + values.length + ' items?' else 'Are you sure you want to remove?'
  $ ->
    $wrapper = $('#'+wrap)
    $('select.selectized,input.selectized', $wrapper).each ->
      $input = $(this)

      update = (e) ->
        if $input.val()
          $('#'+cat).val JSON.stringify($input.val())
        else
          $('#'+cat).val('')

      $(this).on 'change', update
      update()
    

#upload Template SVG

Dropzone.options.temp =
  maxFiles: 1
  acceptedFiles: 'image/svg+xml'
  dictDefaultMessage: 'Drop image here'
  maxFilesize: 100
  createImageThumbnails: true
  thumbnailWidth: '400'
  thumbnailHeight: '300'
  addRemoveLinks: true
  dictInvalidFileType: 'Invalid file!!!'
  accept: (file, done) ->
    done()
  init: ->
    @on 'maxfilesexceeded', (file) ->
      #this.removeFile(file);
      @removeAllFiles()
      @addFile file
    @on 'error', (file) ->
      @removeFile file
    @on 'success', (file, response) ->
      $('#' + response.type + 'svg').attr 'value', response.filename
      $('#' + response.type + 'jpg').attr 'value', response.jpg
      $('#' + response.type + 'svgFolder').attr 'value', response.folder
      $('#' + response.type + 'svgFolder').attr 'value', response.folder
    @on 'removedfile', ->


