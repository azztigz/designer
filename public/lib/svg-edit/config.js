/*globals svgEditor*/

// svgEditor.setConfig({
// extensions: [
// 	'ext-eyedropper.js',
// 	'ext-shapes.js',
// 	'ext-polygon.js',
// 	'ext-star.js'
// ],
// emptyStorageOnDecline: true
// 	allowedOrigins: [window.location.origin] // May be 'null' (as a string) when used as a file:// URL
// });


/*svgEditor.setConfig({
	lang: 'en',
	img_save: 'ref',
	allowedOrigins: [window.location.origin],
	dimensions: [500, 500],
	canvas_expansion: 1,
	imgPath: 'lib/svg-edit/images/',
	jGraduatePath: 'lib/svg-edit/mages/',
	langPath: 'lib/svg-edit/locale/',
	extPath: 'lib/svg-edit/extensions/',
	// extensions: ['ext-server_opensave.js'],
	extensions: [
		//'ext-overview_window.js',
		//'ext-server_opensave.js',
		'ext-php_savefile.js',
	],
	noDefaultExtensions: true,
	noStorageOnLoad: true,
	showlayers: true,
	emptyStorageOnDecline: true
});


$(function(){
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
	//var template = getParam("template")+".svg";
	//svgEditor.loadFromURL("/templates/"+template);
	var link = getParam("link");
	var template = getParam("template");
	svgEditor.loadFromURL(template+'?link='+link);
	
});*/

//svgEditor.loadFromURL("http://backbone.local/svg-edit/templates/Bakery/02/Bakery_bc-04.svg");

// svgEditor.addExtension("Hello World", function() {
        
//         return {
//                 name: 'magente',
// 				svgicons: "extensions/helloworld-icon.xml",
// 				buttons: [{
// 					id: 'tool_star',
// 					type: 'mode',
// 					title: 'Magente',
// 					position: 12,
// 					events: {
// 						click: function(){
// 							alert();
// 						}
// 					}
// 				}],
//         };
// });