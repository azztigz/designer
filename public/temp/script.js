$(function(){
	$('#fotolia_search_button').click(function(){
		$.ajax({
			type: "GET",
			url: "/testing/search",
			data: "words="+$('#fotolia_input').val(),
			success: function(res){
				$('#fotolia_search_result').empty();
				for (key in res) {
					//alert(res[key].thumbnail_url);
					$('#fotolia_search_result').append($(res[key].thumbnail_html_tag).data('id', res[key].id).data('thumbnail_400_url', res[key].thumbnail_400_url));
				}
				$('#fotolia_search_result img').click(importImage);
			}
		});
	});
	
	$('#save_btn').click(buyImages);
	
	function getParam(Name) {
		var Params = location.search.substring(1).split("&");
		var variable = "";
		for (var i = 0; i < Params.length; i++) {
			if(Params[i].split("=")[0] == Name) {
				if (Params[i].split("=").length > 1)
					variable = Params[i].split("=")[1];
				return variable;
			}}
		return "";
	}
	var template = getParam("template")+".svg";
	svgEditor.loadFromURL("/templates/"+template);
	
});

function importImage(){
	convertImgToBase64($(this).data('thumbnail_400_url'), $(this).data('id'), function(base64Img, imageId){
		svgEditor.importImageFromUrl(base64Img, imageId);
		$.ajax({
			type: "GET",
			url: "/testing/get-media",
			data: "id="+imageId,
			success: function(res){
				for (key in res) {
					$('#fotolia'+key).data('sizeRender', $('#fotolia'+key).attr('width') / $('#fotolia'+key).attr('height') );
					imageMedia[key] = res[key];
				}
				countPrice();
			}
		});
	}, 'image/jpg');
}

function convertImgToBase64(url, imageId, callback, outputFormat){
	var canvas = document.createElement('CANVAS');
	var ctx = canvas.getContext('2d');
	var img = new Image;
	img.crossOrigin = 'Anonymous';
	img.onload = function(){
		canvas.height = img.height;
		canvas.width = img.width;
		ctx.drawImage(img,0,0);
		var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		callback.call(this, dataURL, imageId);
		canvas = null;
	};
	img.src = url;
}

var imageMedia = [];
function successLoad(){
	var ids = '';
	$('#workarea image').each(function(){
		$(this).data('sizeRender', $(this).attr('width') / $(this).attr('height') );
		console.log( $(this).data('sizeRender') );
		if($(this).attr('id')){
			if($(this).attr('id').indexOf("fotolia") === 0){
				if(ids){
					ids += ',';
				}
				ids += $(this).attr('id').substr(7);
			}
		}
	});
	
	$.ajax({
		type: "GET",
		url: "/testing/get-media",
		data: "id="+ids,
		success: function(res){
			if (res) {
				imageMedia = res;
			} 
			countPrice();
		}
	});
}

var selectedImage = '';
function countPrice(){
	var prices = 0;
	for (key in imageMedia) {
		var licenses_details = imageMedia[key]['licenses_details'];
		var license = '';
		for (ldkey in licenses_details) {
			if(($('#fotolia'+key).attr('width') < licenses_details[ldkey]['width']) && !license){
				license = licenses_details[ldkey]['license_name'];
			}
		}
		if(!license){
			license = licenses_details[ldkey]['license_name'];
		}
		
		imageMedia[key]['current_license'] = license;
		
		var licenses = imageMedia[key]['licenses'];
		var price = 0;
		for (lkey in licenses){
			if(licenses[lkey]['name'] == license){
				price = licenses[lkey]['price'];
			}
		}
		
		if(selectedImage == key){
			$('#imagePrice').text(price);
		}
		
		prices += price;
	}
	$('#price').text(prices);
}

var buyImageMedia = [];
function buyImages(){
	buyImageMedia = [];
	for (key in imageMedia) {
		buyImageMedia[key] = imageMedia[key];
	}
	buyImage();
}

function buyImage(){
	for (key in buyImageMedia) {
		$.ajax({
			type: "GET",
			url: "/testing/buy-media",
			data: "id="+key+"&license_name="+imageMedia[key]['current_license'],
			success: function(res){
				convertImgToBase64('/fotolia-images/'+res['name'], res['id'], function(base64Img, imageId){
					$('#fotolia'+imageId).attr('xlink:href', base64Img);
					delete buyImageMedia[imageId];
					buyImage();
				}, 'image/'+res['extension']);
			}
		});
		return;
	}
	save();
}

function save(){
	setZoomSave({value: 100});
	var content = '<?xml version="1.0" encoding="UTF-8"?>\n' + $('#svgcontent').wrap('<p/>').parent().html(); 
	$('#svgcontent').unwrap();
	$.post( 
		"/testing/filesave",
		{
			output_svg: content,
			filename: 'template',
			_token: $('body').data('token')
		},
		function () { 
			console.log('success request for saving');
			window.open('/outputs/template.pdf', "width=800, height=600");
		}
	);
}