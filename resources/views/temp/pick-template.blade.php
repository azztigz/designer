<!DOCTYPE html>
<html>
<head>
    <title>UPM - On-line Editor</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge, chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css"/>
    <title>SVG-edit</title>
</head>
<body>
    <div class="container-fluid">
        <div id="The_template_picker_step">
            <h2> Pick the template </h2>
            <a href="{{ route('temp.editor', ['template'=>1]) }}">
                <img src="{{ asset('templates/1.JPG') }}" class="template-images"/>
            </a>
            <a href="{{ route('temp.editor', ['template'=>2]) }}">
                <img src="{{ asset('templates/2.JPG') }}" class="template-images" height="400"/>
            </a>
            <br>
        </div>
    </div>
</body>
</html>

