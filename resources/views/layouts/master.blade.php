<html>
	<head>
	    <title>@yield('title')</title>
	    
	    <link href="{{ asset('css/css/bootstrap.min.css') }}" rel="stylesheet">
	    <link href="{{ asset('css/css/font-awesome.min.css') }}" rel="stylesheet">
	    <link href="{{ asset('css/css/main.css') }}" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
	    <link rel="stylesheet" type="text/css" href="{{ asset('core/selectize/css/selectize.default.css') }}">

	    <script src="{{ asset('core/jquery.js') }}"></script>
	    <script src="{{ asset('core/bootstrap.min.js') }}"></script>
	    <script src="{{ asset('core/json2.js') }}"></script>
	    <script src="{{ asset('core/underscore.js') }}"></script>
	    <script src="{{ asset('core/backbone.js') }}"></script>
	    <script src="{{ asset('core/backbone.marionette.js') }}"></script>

	    <script src="{{ asset('core/dropzone.js') }}"></script>
	    <script src="{{ asset('core/wookmark.js') }}"></script>
	    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.1.8/imagesloaded.pkgd.min.js"></script>
  </head>
    <body>

        @yield('content')
 
    </body>
</html>