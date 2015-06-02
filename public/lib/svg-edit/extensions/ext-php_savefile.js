/*globals $, svgCanvas, svgEditor*/
/*jslint regexp:true*/
// TODO: Might add support for "exportImage" custom
//   handler as in "ext-server_opensave.js" (and in savefile.php)

svgEditor.addExtension("php_savefile", {
	callback: function() {
		'use strict';

		function getFileNameFromTitle () {
			var title = svgCanvas.getDocumentTitle();
			return $.trim(title);
		}
		//var save_svg_action = svgEditor.curConfig.extPath + 'savefile.php';

		var editorlink = $('#editorlink').attr('value');
		var save_svg_action = 'editorSaveFile?link='+editorlink; 
		var svg_id = $('#svg_id').attr('value');
		var svg_type = $('#svg_type').attr('value');
		var svg_work_id = $('#svg_work_id').attr('value');

		svgEditor.setCustomHandlers({
			save: function(win, data) {
				var svg = '<?xml version="1.0" encoding="UTF-8"?>\n' + data,
					filename = getFileNameFromTitle();

				var items = {};

				$('#workarea image').each(function(){
					if($(this).attr('price') && $(this).attr('price') > 0){
						var id = $(this).attr('id');
						var price = $(this).attr('price');
						var license = $(this).attr('license');
						items[id] = {image_id:id,license:license,price:price,image:libs.infos[id]}; 
					}
				});

				$.post(save_svg_action, {
					output_svg: svg, 
					filename: filename,
					svg_id: svg_id,
					svg_type: svg_type,
					svg_work_id: svg_work_id,
					svg_images: items, 
					saveType: 'save'
				}).done(function(){
					//$('#msg').text("Saved at " + new Date());
					$('#msg').text("Saved");
					setInterval(function(){ 
						$('#msg').fadeOut();
					}, 3000);
				});
			},
			exportImage: function(win, data) {
				var svg = '<?xml version="1.0" encoding="UTF-8"?>\n' + data.svg;
				$.post(save_svg_action, {
					output_svg: svg, 
					filename: 'image',
					svg_id: svg_id,
					svg_type: svg_type,
					saveType: 'export'
				}).done(function(){
					alert('Successfully exported!!!');
				});
			}
		});
	}
});
