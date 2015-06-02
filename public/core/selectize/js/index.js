$(function() {
	//var $wrapper = $('#wrapper');

	// $('select.selectized,input.selectized', $wrapper).each(function() {
	// 	var $container = $('<div>').addClass('value').html('Current Value: ');
	// 	var $value = $('<span>').appendTo($container);
	// 	var $input = $(this);
	// 	var update = function(e) { $value.text(JSON.stringify($input.val())); }

	// 	$(this).on('change', update);
	// 	update();

	// 	$container.insertAfter($input);
	// });

	var $wrapper = $('#wrapper');

	$('select.selectized,input.selectized', $wrapper).each(function() {
		//var $container = $('<div>').addClass('value').html('Current Value: ');
		//var $value = $('<span>').appendTo($container);
		var $input = $(this);
		var update = function(e) { $('#categories').val(JSON.stringify($input.val())); }

		$(this).on('change', update);
		update();

		//$container.insertAfter($input);

		// $('#categories').val(JSON.stringify($input.val()));
		// $(this).on('change', function(){
		// 	$('#categories').val(JSON.stringify($input.val()));
		// });
		
	});

});

$('#select-state').selectize({
	plugins: ['remove_button'],
	maxItems: null,
	onDelete: function(values) {
		//return confirm(values.length > 1 ? 'Are you sure you want to remove these ' + values.length + ' items?' : 'Are you sure you want to remove "' + values[0] + '"?');
		return confirm(values.length > 1 ? 'Are you sure you want to remove these ' + values.length + ' items?' : 'Are you sure you want to remove?');
	}
});

Dropzone.options.temp = {
  maxFiles: 1,
  acceptedFiles: 'image/*',
  dictDefaultMessage: "Drop image here",
  maxFilesize: 10,
  createImageThumbnails: true,
  thumbnailWidth: "400",
  thumbnailHeight: "300",
  addRemoveLinks: true,
  dictInvalidFileType: 'Invalid file!!!',
  accept: function(file, done) {
    done();
  },
  init: function() {
    this.on("maxfilesexceeded", function(file){
        //this.removeFile(file);
        this.removeAllFiles();
		this.addFile(file);
    });
    this.on("error", function(file){
        this.removeFile(file);
    });
    this.on("success", function(file, response) {
    	$('#'+response.type+'svg').attr("value", response.filename);
    });
    this.on("removedfile", function(){
    	// $.ajax({
	    //     type: 'POST',
	    //     url: 'delete.php',
	    //     data: "id="+name,
	    //     dataType: 'html'
    	// });
    });
    // this.on("thumbnail", function(file, dataUrl){
    // 	alert(dataUrl);
    // });
  }
};
