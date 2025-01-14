/*globals svgEditor, svgCanvas, $*/
/*jslint vars: true, eqeq: true*/
/*
 * ext-library.js
 *
 * Licensed under the MIT License
 *
 * Copyright(c) 2015 MOA
 *
 */
 
 
svgEditor.addExtension("Library", function() {'use strict';

		return {
			name: "Library",
			// For more notes on how to make an icon file, see the source of
			// the hellorworld-icon.xml
			svgicons: svgEditor.curConfig.extPath + "library-icon.xml",
			
			// Multiple buttons can be added in this array
			buttons: [{
				// Must match the icon ID in helloworld-icon.xml
				id: "change_image", 
				
				// This indicates that the button will be added to the "mode"
				// button panel on the left side
				type: "mode", 
				
				// Tooltip text
				title: "Library", 
				
				// Events
				events: {
					'click': function() {
						$('#myModal').modal('show');
					}
				}
			}],
			// // This is triggered when the main mouse button is pressed down 
			// // on the editor canvas (not the tool panels)
			// mouseDown: function() {
			// 	// Check the mode on mousedown
			// 	if(svgCanvas.getMode() == "hello_world") {
				
			// 		// The returned object must include "started" with 
			// 		// a value of true in order for mouseUp to be triggered
			// 		return {started: true};
			// 	}
			// },
			
			// // This is triggered from anywhere, but "started" must have been set
			// // to true (see above). Note that "opts" is an object with event info
			// mouseUp: function(opts) {
			// 	// Check the mode on mouseup
			// 	if(svgCanvas.getMode() == "hello_world") {
			// 		var zoom = svgCanvas.getZoom();
					
			// 		// Get the actual coordinate by dividing by the zoom value
			// 		var x = opts.mouse_x / zoom;
			// 		var y = opts.mouse_y / zoom;
					
			// 		var text = "Hello World!\n\nYou clicked here: " 
			// 			+ x + ", " + y;
						
			// 		// Show the text using the custom alert function
			// 		$.alert(text);
			// 	}
			// }
		};
});

