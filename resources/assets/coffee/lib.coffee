"use strict";

libs = new (Marionette.Application)

libs.addRegions
  topRegion:  '#top_region'
  contentRegion: '#content_region'
  photosRegion: '#savedphotos'
  searchRegion: '#search_region'

libs.Lists = Backbone.Model.extend();

libs.ListsCollection = Backbone.Collection.extend(
  model: libs.Lists 
)


libs.lists = new (libs.ListsCollection)([
  {
    'id': '1'
    'name': 'Saved Photos'
    'slug': 'saved_photos'
  }
  {
    'id': '2'
    'name': 'Computer'
    'slug': 'computer'
  }
  {
    'id': '3'
    'name': 'Buy Photos'
    'slug': 'buy_photos'
  }
  {
    'id': '4'
    'name': 'Facebook'
    'slug': 'facebook'
  }
  {
    'id': '5'
    'name': 'Instagram'
    'slug': 'instagram'
  }
  {
    'id': '6'
    'name': 'Flickr'
    'slug': 'flickr'
  }
])

libs.fot = ''

libs.infos = []

libs.module 'App', (App, libs, Backbone, Marionette, $, _) ->

  App.currentpage = ''
  App.temp = 'Templates'
  App.page = 'saved_photos'
  App.link = $('#editorlink').attr('value')
  App.id = ''

  App.getInfo = (col, vw, ur, reg) ->
    col = new (libs.ListsCollection)()
    col.fetch
      url: ur
      success: ->
        layout = new (vw)(
          collection: col
        )
        reg.show layout
      error: ->
        console.log 'No result found!!!'

  App.getFotolia = (col, ur, id) ->
    col = new (libs.Lists)()
    col.fetch
      url: ur
      success: (col, response)->
        response
      error: ->
        console.log 'No result found!!!'
    col

  App.Route = Backbone.Marionette.AppRouter.extend
    routes:
      '': 'view'
      ':page': 'view'
    view: (page) ->
      if page == null
        page = App.page
      App.currentpage = page;

      if page == 'saved_photos'
        App.getInfo 'photos', App.PhotosView, '/editor/photos?link='+App.link, libs.photosRegion
      if page == 'buy_photos'
        layout = new App.SearchView
        libs.searchRegion.show layout

      # layout = new App.PhotosView
      # libs.photosRegion.show layout

  App.defaultView = Marionette.LayoutView.extend
    onAttach: ->
      $('.' + App.currentpage).addClass 'active'
    initialize: ->
      # $('#myModal').modal 'show'
      @router = new (App.Route)
      if !Backbone.History.started
        Backbone.history.start()
    template: '#top_template'
    events:
      'click #myTab li a': 'checkA'
    checkA: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')
      $('.tab-pane').each ->
       $('div#' + @id).removeClass 'active'

      $('.tab-pane#' + id).addClass 'active'
      @router.navigate id, true

  App.PhotosView = Marionette.LayoutView.extend
    template: '#saved_template'
    templateHelpers: ->
      { 
        link: ->
          "?link="+App.link
      }
    onShow: ->
      do ->
        $('.overlay').css('display','block')
        wookmark = undefined

        getWindowWidth = ->
          Math.max document.documentElement.clientWidth, window.innerWidth or 0

        imagesLoaded '#container', ->
          $('.overlay').css('display','none')
          wookmark = new Wookmark('#container',
            itemWidth: 150
            outerOffset: 20
            flexibleWidth: ->
              if getWindowWidth() < 1024 then '100%' else '50%'
          )

    events:
      "click img":"checkImg"
    checkImg: (e) ->
      clickedEl = $(e.currentTarget)
      id = clickedEl.attr('id')
      src = clickedEl.attr('src')
      elem_id = $('#elem_id').val();
      importImage src, $('#elem_id').val(), $('#image_width').val(), $('#image_height').val(), $('#'+elem_id).attr('x'), $('#'+elem_id).attr('y')
      $('#myModal').modal 'hide'

  App.SearchView = Marionette.LayoutView.extend
    template: '#search_template'
    regions:
      fotolia:'#fotolia_wrapper'
    ui:
      input: '#searchval',
      button: '#search'
    events:
      "click @ui.button":"searchImg"
      "change @ui.input":"searchImg"
    searchImg: () ->
      search = @ui.input.val()
      $('.overlay').css('display','block')
      App.getInfo 'search', App.FotoliaView, '/editor/search?words='+search+'&link='+App.link, @fotolia

  App.FotoliaView = Marionette.ItemView.extend
    template: '#fotolia_template'  
    onShow: ->
      do ->
        # $('.overlay').css('display','block')
        wookmark = undefined

        getWindowWidth = ->
          Math.max document.documentElement.clientWidth, window.innerWidth or 0

        imagesLoaded '#container2', ->
          $('.overlay').css('display','none')
          wookmark = new Wookmark('#container2',
            itemWidth: 150
            outerOffset: 20
            flexibleWidth: ->
              if getWindowWidth() < 1024 then '100%' else '50%'
          )
    events:
      'click img':'getImg'
      'click #infoClose':'closeInfo'
      'click #insert_image':'getVal'
    getImg: (e) ->
      id = $(e.currentTarget).attr('id')
      $('#info_'+id).modal 'show'
    closeInfo: (e) ->
      id = $(e.currentTarget).attr('data-id')
      $('#'+id).modal('hide')
    getVal: (e) ->
      id = $(e.currentTarget).attr('id')
      fotolia_id = $(e.currentTarget).attr('data-id')
      price = $(e.currentTarget).attr('data-price')
      license = $(e.currentTarget).attr('data-license')
      src = $(e.currentTarget).attr('data-src')

      fot = $('#elem_id').val()

      fotolia = new (libs.Lists)()
      fotolia.fetch
        url: '/editor/getmedia?id='+fotolia_id+'&link='+App.link
        success: (col, response)->
          items = ''
          _.each response, (item) ->
            items = item

          libs.infos[fot] = items;
          elem_id = $('#elem_id').val()
          importImage src, $('#elem_id').val(), price, $('#image_width').val(), $('#image_height').val(), $('#'+elem_id).attr('x'), $('#'+elem_id).attr('y'), license
          $('#elem_id').attr('data-price',price);
          $('#elem_id').attr('data-license',license);
          $('#myModal').modal 'hide'

        error: ->
          console.log 'No result found!!!'


      

      
  App.addInitializer ->
    layout = new (App.defaultView)(collection: libs.lists)
    libs.topRegion.show layout 




previewNode = document.querySelector('#template')
previewNode.id = ''
previewTemplate = previewNode.parentNode.innerHTML
previewNode.parentNode.removeChild previewNode

myDropzone = new Dropzone(document.body,
  url: '/editor/uploadFile?link='+$('#editorlink').attr('value')
  acceptedFiles: 'image/*'
  # maxFilesize: 10,
  thumbnailWidth: 80
  thumbnailHeight: 80
  parallelUploads: 20
  previewTemplate: previewTemplate
  autoQueue: false
  previewsContainer: '#previews'
  clickable: '.fileinput-button')

myDropzone.on 'addedfile', (file) ->
  file.previewElement.querySelector('.start').onclick = ->
    myDropzone.enqueueFile file

myDropzone.on 'totaluploadprogress', (progress) ->
  document.querySelector('#total-progress .progress-bar').style.width = progress + '%'

myDropzone.on 'sending', (file) ->
  document.querySelector('#total-progress').style.opacity = '1'
  file.previewElement.querySelector('.start').setAttribute 'disabled', 'disabled'

myDropzone.on 'queuecomplete', (progress) ->
  document.querySelector('#total-progress').style.opacity = '0'

document.querySelector('#actions .start').onclick = ->
  myDropzone.enqueueFiles myDropzone.getFilesWithStatus(Dropzone.ADDED)

document.querySelector('#actions .cancel').onclick = ->
  myDropzone.removeAllFiles true

myDropzone.on 'error', (file) ->
  @removeFile(file)
  alert 'Invalid file!!!'


importImage = (url, id, price, imgw, imgh, x, y, license) ->
  convertImgToBase64 url, id, ((base64Img, imageId) ->
    svgEditor.importImageFromUrl base64Img, imageId, price, imgw, imgh, x, y, license
    return
  ), 'image/jpg'

convertImgToBase64 = (url, imageId, callback, outputFormat) ->
  canvas = document.createElement('CANVAS')
  ctx = canvas.getContext('2d')
  img = new Image
  img.crossOrigin = 'Anonymous'

  img.onload = ->
    canvas.height = img.height
    canvas.width = img.width
    ctx.drawImage img, 0, 0
    dataURL = canvas.toDataURL(outputFormat or 'image/png')
    callback.call this, dataURL, imageId
    canvas = null

  img.src = url
