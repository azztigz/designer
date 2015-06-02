/*globals svgEditor*/

svgEditor.setConfig({
	lang: 'en',
	img_save: 'ref',
	allowedOrigins: [window.location.origin],
	dimensions: [500, 500],
	canvas_expansion: 1,
	imgPath: '/lib/svg-edit-2.7/images/',
	jGraduatePath: '/lib/svg-edit-2.7/jgraduate/images/',
	langPath: '/lib/svg-edit-2.7/locale/',
	extPath: '/lib/svg-edit-2.7/extensions/',
	extensions: ['ext-server_opensave.js'],
	noDefaultExtensions: true,
	noStorageOnLoad: true
});
