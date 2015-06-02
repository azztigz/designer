"use strict";
var convertImgToBase64, importImage, libs, myDropzone, previewNode, previewTemplate;

libs = new Marionette.Application;

libs.addRegions({
  topRegion: '#top_region',
  contentRegion: '#content_region',
  photosRegion: '#savedphotos',
  searchRegion: '#search_region'
});

libs.Lists = Backbone.Model.extend();

libs.ListsCollection = Backbone.Collection.extend({
  model: libs.Lists
});

libs.lists = new libs.ListsCollection([
  {
    'id': '1',
    'name': 'Saved Photos',
    'slug': 'saved_photos'
  }, {
    'id': '2',
    'name': 'Computer',
    'slug': 'computer'
  }, {
    'id': '3',
    'name': 'Buy Photos',
    'slug': 'buy_photos'
  }, {
    'id': '4',
    'name': 'Facebook',
    'slug': 'facebook'
  }, {
    'id': '5',
    'name': 'Instagram',
    'slug': 'instagram'
  }, {
    'id': '6',
    'name': 'Flickr',
    'slug': 'flickr'
  }
]);

libs.fot = '';

libs.infos = [];

libs.module('App', function(App, libs, Backbone, Marionette, $, _) {
  App.currentpage = '';
  App.temp = 'Templates';
  App.page = 'saved_photos';
  App.link = $('#editorlink').attr('value');
  App.id = '';
  App.getInfo = function(col, vw, ur, reg) {
    col = new libs.ListsCollection();
    return col.fetch({
      url: ur,
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
  App.getFotolia = function(col, ur, id) {
    col = new libs.Lists();
    col.fetch({
      url: ur,
      success: function(col, response) {
        return response;
      },
      error: function() {
        return console.log('No result found!!!');
      }
    });
    return col;
  };
  App.Route = Backbone.Marionette.AppRouter.extend({
    routes: {
      '': 'view',
      ':page': 'view'
    },
    view: function(page) {
      var layout;
      if (page === null) {
        page = App.page;
      }
      App.currentpage = page;
      if (page === 'saved_photos') {
        App.getInfo('photos', App.PhotosView, '/editor/photos?link=' + App.link, libs.photosRegion);
      }
      if (page === 'buy_photos') {
        layout = new App.SearchView;
        return libs.searchRegion.show(layout);
      }
    }
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
    template: '#top_template',
    events: {
      'click #myTab li a': 'checkA'
    },
    checkA: function(e) {
      var clickedEl, id;
      clickedEl = $(e.currentTarget);
      id = clickedEl.attr('id');
      $('.tab-pane').each(function() {
        return $('div#' + this.id).removeClass('active');
      });
      $('.tab-pane#' + id).addClass('active');
      return this.router.navigate(id, true);
    }
  });
  App.PhotosView = Marionette.LayoutView.extend({
    template: '#saved_template',
    templateHelpers: function() {
      return {
        link: function() {
          return "?link=" + App.link;
        }
      };
    },
    onShow: function() {
      return (function() {
        var getWindowWidth, wookmark;
        $('.overlay').css('display', 'block');
        wookmark = void 0;
        getWindowWidth = function() {
          return Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        };
        return imagesLoaded('#container', function() {
          $('.overlay').css('display', 'none');
          return wookmark = new Wookmark('#container', {
            itemWidth: 150,
            outerOffset: 20,
            flexibleWidth: function() {
              if (getWindowWidth() < 1024) {
                return '100%';
              } else {
                return '50%';
              }
            }
          });
        });
      })();
    },
    events: {
      "click img": "checkImg"
    },
    checkImg: function(e) {
      var clickedEl, elem_id, id, src;
      clickedEl = $(e.currentTarget);
      id = clickedEl.attr('id');
      src = clickedEl.attr('src');
      elem_id = $('#elem_id').val();
      importImage(src, $('#elem_id').val(), $('#image_width').val(), $('#image_height').val(), $('#' + elem_id).attr('x'), $('#' + elem_id).attr('y'));
      return $('#myModal').modal('hide');
    }
  });
  App.SearchView = Marionette.LayoutView.extend({
    template: '#search_template',
    regions: {
      fotolia: '#fotolia_wrapper'
    },
    ui: {
      input: '#searchval',
      button: '#search'
    },
    events: {
      "click @ui.button": "searchImg",
      "change @ui.input": "searchImg"
    },
    searchImg: function() {
      var search;
      search = this.ui.input.val();
      $('.overlay').css('display', 'block');
      return App.getInfo('search', App.FotoliaView, '/editor/search?words=' + search + '&link=' + App.link, this.fotolia);
    }
  });
  App.FotoliaView = Marionette.ItemView.extend({
    template: '#fotolia_template',
    onShow: function() {
      return (function() {
        var getWindowWidth, wookmark;
        wookmark = void 0;
        getWindowWidth = function() {
          return Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        };
        return imagesLoaded('#container2', function() {
          $('.overlay').css('display', 'none');
          return wookmark = new Wookmark('#container2', {
            itemWidth: 150,
            outerOffset: 20,
            flexibleWidth: function() {
              if (getWindowWidth() < 1024) {
                return '100%';
              } else {
                return '50%';
              }
            }
          });
        });
      })();
    },
    events: {
      'click img': 'getImg',
      'click #infoClose': 'closeInfo',
      'click #insert_image': 'getVal'
    },
    getImg: function(e) {
      var id;
      id = $(e.currentTarget).attr('id');
      return $('#info_' + id).modal('show');
    },
    closeInfo: function(e) {
      var id;
      id = $(e.currentTarget).attr('data-id');
      return $('#' + id).modal('hide');
    },
    getVal: function(e) {
      var fot, fotolia, fotolia_id, id, license, price, src;
      id = $(e.currentTarget).attr('id');
      fotolia_id = $(e.currentTarget).attr('data-id');
      price = $(e.currentTarget).attr('data-price');
      license = $(e.currentTarget).attr('data-license');
      src = $(e.currentTarget).attr('data-src');
      fot = $('#elem_id').val();
      fotolia = new libs.Lists();
      return fotolia.fetch({
        url: '/editor/getmedia?id=' + fotolia_id + '&link=' + App.link,
        success: function(col, response) {
          var elem_id, items;
          items = '';
          _.each(response, function(item) {
            return items = item;
          });
          libs.infos[fot] = items;
          elem_id = $('#elem_id').val();
          importImage(src, $('#elem_id').val(), price, $('#image_width').val(), $('#image_height').val(), $('#' + elem_id).attr('x'), $('#' + elem_id).attr('y'), license);
          $('#elem_id').attr('data-price', price);
          $('#elem_id').attr('data-license', license);
          return $('#myModal').modal('hide');
        },
        error: function() {
          return console.log('No result found!!!');
        }
      });
    }
  });
  return App.addInitializer(function() {
    var layout;
    layout = new App.defaultView({
      collection: libs.lists
    });
    return libs.topRegion.show(layout);
  });
});

previewNode = document.querySelector('#template');

previewNode.id = '';

previewTemplate = previewNode.parentNode.innerHTML;

previewNode.parentNode.removeChild(previewNode);

myDropzone = new Dropzone(document.body, {
  url: '/editor/uploadFile?link=' + $('#editorlink').attr('value'),
  acceptedFiles: 'image/*',
  thumbnailWidth: 80,
  thumbnailHeight: 80,
  parallelUploads: 20,
  previewTemplate: previewTemplate,
  autoQueue: false,
  previewsContainer: '#previews',
  clickable: '.fileinput-button'
});

myDropzone.on('addedfile', function(file) {
  return file.previewElement.querySelector('.start').onclick = function() {
    return myDropzone.enqueueFile(file);
  };
});

myDropzone.on('totaluploadprogress', function(progress) {
  return document.querySelector('#total-progress .progress-bar').style.width = progress + '%';
});

myDropzone.on('sending', function(file) {
  document.querySelector('#total-progress').style.opacity = '1';
  return file.previewElement.querySelector('.start').setAttribute('disabled', 'disabled');
});

myDropzone.on('queuecomplete', function(progress) {
  return document.querySelector('#total-progress').style.opacity = '0';
});

document.querySelector('#actions .start').onclick = function() {
  return myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
};

document.querySelector('#actions .cancel').onclick = function() {
  return myDropzone.removeAllFiles(true);
};

myDropzone.on('error', function(file) {
  this.removeFile(file);
  return alert('Invalid file!!!');
});

importImage = function(url, id, price, imgw, imgh, x, y, license) {
  return convertImgToBase64(url, id, (function(base64Img, imageId) {
    svgEditor.importImageFromUrl(base64Img, imageId, price, imgw, imgh, x, y, license);
  }), 'image/jpg');
};

convertImgToBase64 = function(url, imageId, callback, outputFormat) {
  var canvas, ctx, img;
  canvas = document.createElement('CANVAS');
  ctx = canvas.getContext('2d');
  img = new Image;
  img.crossOrigin = 'Anonymous';
  img.onload = function() {
    var dataURL;
    canvas.height = img.height;
    canvas.width = img.width;
    ctx.drawImage(img, 0, 0);
    dataURL = canvas.toDataURL(outputFormat || 'image/png');
    callback.call(this, dataURL, imageId);
    return canvas = null;
  };
  return img.src = url;
};

//# sourceMappingURL=lib.js.map