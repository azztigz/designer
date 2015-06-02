<!DOCTYPE html>
<html>
<!-- removed for now, causes problems in Firefox: manifest="svg-editor.manifest" -->
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge, chrome=1"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<link rel="icon" type="image/png" href="{{ asset('lib/svg-edit/extensions/imagelib/SVG/svg.svg') }}"/>

<link rel="stylesheet" href="{{ asset('lib/svg-edit/jgraduate/css/jPicker.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('lib/svg-edit/jgraduate/css/jgraduate.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('lib/svg-edit/svg-editor.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('lib/svg-edit/spinbtn/JQuerySpinBtn.css') }}" type="text/css"/>
<script src="{{ asset('core/jquery.js') }}"></script>
<!--{if jquery_release}>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<!{else}-->

  <!-- <script src="{{ asset('lib/svg-edit/jquery.js') }}"></script> -->
<!--{endif}-->

<script src="{{ asset('lib/svg-edit/js-hotkeys/jquery.hotkeys.min.js') }}"></script>
<script src="{{ asset('lib/svg-edit/jquerybbq/jquery.bbq.min.js') }}"></script>
<script src="{{ asset('lib/svg-edit/svgicons/jquery.svgicons.js') }}"></script>
<script src="{{ asset('lib/svg-edit/jgraduate/jquery.jgraduate.min.js') }}"></script>
<script src="{{ asset('lib/svg-edit/spinbtn/JQuerySpinBtn.min.js') }}"></script>
<script src="{{ asset('lib/svg-edit/touch.js') }}"></script>

<script src="{{ asset('lib/svg-edit/svgedit.js') }}"></script>
<script src="{{ asset('lib/svg-edit/jquery-svg.js') }}"></script>
<script src="{{ asset('lib/svg-edit/contextmenu/jquery.contextMenu.js') }}"></script>
<script src="{{ asset('lib/svg-edit/browser.js') }}"></script>
<script src="{{ asset('lib/svg-edit/svgtransformlist.js') }}"></script>
<script src="{{ asset('lib/svg-edit/math.js') }}"></script>
<script src="{{ asset('lib/svg-edit/units.js') }}"></script>
<script src="{{ asset('lib/svg-edit/svgutils.js') }}"></script>
<script src="{{ asset('lib/svg-edit/sanitize.js') }}"></script>
<script src="{{ asset('lib/svg-edit/history.js') }}"></script>
<script src="{{ asset('lib/svg-edit/coords.js') }}"></script>
<script src="{{ asset('lib/svg-edit/recalculate.js') }}"></script>

<script src="{{ asset('lib/svg-edit/draw.js') }}"></script>
<script src="{{ asset('lib/svg-edit/path.js') }}"></script>
<script src="{{ asset('lib/svg-edit/svgcanvas.js') }}"></script>
<script src="{{ asset('lib/svg-edit/svg-editor.js') }}"></script>
<script src="{{ asset('lib/svg-edit/locale/locale.js') }}"></script>
<script src="{{ asset('lib/svg-edit/contextmenu.js') }}"></script>

<!--{if svg_edit_release}-->

  <!-- <script src="{{ asset('lib/svg-edit/svgedit.compiled.js') }}"></script> -->
<!--{else}>

  <script src="svgedit.js"></script>
  <script src="jquery-svg.js"></script>
  <script src="contextmenu/jquery.contextMenu.js"></script>
  <script src="browser.js"></script>
  <script src="svgtransformlist.js"></script>
  <script src="math.js"></script>
  <script src="units.js"></script>
  <script src="svgutils.js"></script>
  <script src="sanitize.js"></script>
  <script src="history.js"></script>
  <script src="coords.js"></script>
  <script src="recalculate.js"></script>
  <script src="select.js"></script>
  <script src="draw.js"></script>
  <script src="path.js"></script>
  <script src="svgcanvas.js"></script>
  <script src="svg-editor.js"></script>
  <script src="locale/locale.js"></script>
  <script src="contextmenu.js"></script>
<!{endif}-->


<!-- If you do not wish to add extensions by URL, you can load them
by creating the following file and adding by calls to svgEditor.setConfig -->
<script src="{{ asset('lib/svg-edit/config.js') }}"></script>

<!-- always minified scripts -->
<script src="{{ asset('lib/svg-edit/jquery-ui/jquery-ui-1.8.17.custom.min.js') }}"></script>
<script src="{{ asset('lib/svg-edit/jgraduate/jpicker.js') }}"></script>

<!-- feeds -->
<link rel="alternate" type="application/atom+xml" title="SVG-edit General Discussion" href="http://groups.google.com/group/svg-edit/feed/atom_v1_0_msgs.xml" />
<link rel="alternate" type="application/atom+xml" title="SVG-edit Updates (Issues/Fixes/Commits)" href="http://code.google.com/feeds/p/svg-edit/updates/basic" />

<!-- Add script with custom handlers here -->


<script type="text/javascript">

    svgEditor.setConfig({
        lang: 'en',
        img_save: 'ref',
        allowedOrigins: [window.location.origin],
        //dimensions: [1024, 768],
        canvas_expansion: 1,
        imgPath: '{{ asset("lib/svg-edit/images/")."/" }}',
        jGraduatePath: '{{ asset("lib/svg-edit/images/")."/" }}',
        langPath: '{{ asset("lib/svg-edit/locale/")."/" }}',
        extPath: '{{ asset("lib/svg-edit/extensions/")."/" }}',
        extensions: [
            'ext-php_savefile.js',
            // 'ext-library.js',
        ],
        noDefaultExtensions: true,
        noStorageOnLoad: true,
        //emptyStorageOnDecline: true,
        showRulers: false
    });


    $(function(){
        svgEditor.loadFromURL("<?php echo url($svg); ?>");

        $("#change_image").click(function(){
            $('#myModal').modal('show');
        });
    });



    // function importImage(url, id){
    //     convertImgToBase64(url, id, function(base64Img, imageId){
    //         svgEditor.importImageFromUrl(base64Img, imageId);
    //     }, 'image/jpg');
    // }

    // function convertImgToBase64(url, imageId, callback, outputFormat){
    //     var canvas = document.createElement('CANVAS');
    //     var ctx = canvas.getContext('2d');
    //     var img = new Image;
    //     img.crossOrigin = 'Anonymous';
    //     img.onload = function(){
    //         canvas.height = img.height;
    //         canvas.width = img.width;
    //         ctx.drawImage(img,0,0);
    //         var dataURL = canvas.toDataURL(outputFormat || 'image/png');
    //         callback.call(this, dataURL, imageId);
    //         canvas = null;
    //     };
    //     img.src = url;
    // }
        

                

</script>
<form>
<input type="hidden" id="asscanv" name="asscanv" value="<?php echo url(); ?>">
<input type="hidden" id="editorlink" name="editorlink" value="<?php echo Session::get('user')->link; ?>">
<input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">
<input type="hidden" id="svg_id" name="svg_id" value="<?php echo $svg_id; ?>">
<input type="hidden" id="svg_type" name="svg_type" value="<?php echo $svg_type; ?>">
<input type="hidden" id="svg_work_id" name="svg_work_id" value="<?php echo $svg_work_id; ?>">

<input type="hidden" id="svgImageHeight" value="0" name="svgImageHeight">
<input type="hidden" id="svgImageWidth" value="0" name="svgImageWidth">
<input type="hidden" id="svgImageLeft" value="0" name="svgImageLeft">
<input type="hidden" id="svgImageTop" value="0" name="svgImageTop">

<?php 
    
    $exist = 0;

    if($images){ 
        $exist = 1;
    ?>
    <?php    
        foreach($images as $item){
            ?>
    <input type='hidden' name='<?php echo $item->svg_id; ?>_price' id='<?php echo $item->svg_id; ?>_price' value='<?php echo $item->price; ?>'>

    <input type='hidden' name='<?php echo $item->svg_id; ?>_license' id='<?php echo $item->svg_id; ?>_license' value='<?php echo $item->license; ?>'>
                
            <?php  
        }
    }
        
     
?>

<input type='hidden' id='svg_exist' value='<?php echo $exist; ?>'>

<title>SVG Editor</title>
</head>
<body>

<div id="rightdiv">
    <span class="label label-success">Layer:</span><br/>
    <br/>
    <div id="svglists">
    </div>
</div>

<div id="svg_wrapper">
<div id="svg_editor">

<div id="rulers">
    <div id="ruler_corner"></div>
    <div id="ruler_x">
        <div>
            <canvas height="15"></canvas>
        </div>
    </div>
    <div id="ruler_y">
        <div>
            <canvas width="15"></canvas>
        </div>
    </div>
</div>

<div id="workarea">
<style id="styleoverrides" type="text/css" media="screen" scoped="scoped"></style>
<div id="svgcanvas" style="position:relative">

</div>
</div>

<!-- <div id="sidepanels">
    <div id="layerpanel">
        <h3 id="layersLabel">Layers</h3>
        <fieldset id="layerbuttons">
            <div id="layer_new" class="layer_button"  title="New Layer"></div>
            <div id="layer_delete" class="layer_button"  title="Delete Layer"></div>
            <div id="layer_rename" class="layer_button"  title="Rename Layer"></div>
            <div id="layer_up" class="layer_button"  title="Move Layer Up"></div>
            <div id="layer_down" class="layer_button"  title="Move Layer Down"></div>
            <div id="layer_moreopts" class="layer_button"  title="More Options"></div>
        </fieldset>
        
        <table id="layerlist">
            <tr class="layer">
                <td class="layervis"></td>
                <td class="layername">Layer 1</td>
            </tr>
        </table>
        <span id="selLayerLabel">Move elements to:</span>
        <select id="selLayerNames" title="Move selected elements to a different layer" disabled="disabled">
            <option selected="selected" value="layer1">Layer 1</option>
        </select>
    </div>
    <div id="sidepanel_handle" title="Drag left/right to resize side panel [X]">L a y e r s</div>
</div> -->

<!-- <div id="main_button">
    <div id="main_icon" class="tool_button" title="Main Menu">
        <i class="fa fa-edit" style="padding-left:10px; font-size:18px;"></i>
        <span>SVG Editor</span> -->
        <!-- <div id="logo"></div> -->
        <!-- <div class="dropdown"></div>
    </div> -->
        
   <!--  <div id="main_menu">  -->
    
        <!-- File-like buttons: New, Save, Source -->
       <!--  <ul> -->
           <!--  <li id="tool_clear">
                <div></div>
                New Image (N)
            </li>
            
            <li id="tool_open" style="display:none;">
                <div id="fileinputs">
                    <div></div>
                </div>
                Open Image
            </li>
            
            <li id="tool_import" style="display:none;">
                <div id="fileinputs_import">
                    <div></div>
                </div>
                Import Image
            </li> -->
            
           <!--  <li id="tool_save"> -->
                <!-- <div></div> -->
                <!-- <i class="fa fa-save" style="padding-left:10px; font-size:18px;"></i>
                Save Image (S)
            </li> -->
            
           <!--  <li id="tool_export">
                <div></div>
                Export
            </li>
            
            <li id="tool_docprops">
                <div></div>
                Document Properties (D)
            </li> -->
       <!--  </ul> -->

        <!-- <p>
            <a href="http://svg-edit.googlecode.com/" target="_blank">
                SVG-edit Home Page
            </a>
        </p>

        <button id="tool_prefs_option">
            Editor Options
        </button> -->


    <!-- </div>
</div> -->

<div class="container">



    <div class="clearfix" style="height:10px;"></div>
    <div class="col-md-6">
         <button type="button" class="btn btn-success btn-xs" id="tool_save" title="Cmd+">
            <i class="fa fa-save" style="padding-left:10px;"></i> &nbsp;Save
        </button>
        | 
        <a href="<?php echo url('editor?link='.Session::get('user')->link); ?>" style="text-decoration:none;">
            <button type="button" class="btn btn-primary btn-xs">
                <i class="fa fa-external-link-square"></i> Change Template
            </button>
        </a>
        <a id="msg">
        </a>
         <a style="text-decoration:none;" class="pull-right">
            <button type="button" class="btn btn-default btn-xs" id="zoomin">
                <i class="fa fa-search-plus"></i> Zoom In
            </button>
        </a>
    </div>
   
    <div class="col-md-6 text-right">

    
        <a style="text-decoration:none;" class="pull-left">
            <button type="button" class="btn btn-default btn-xs" id="zoomout">
                <i class="fa fa-search-minus"></i> Zoom Out
            </button>
        </a>

        <a href="<?php echo url($edit); ?>" style="text-decoration:none;">
            <button type="button" class="btn btn-primary btn-xs">
                <i class="fa fa-repeat"></i> Flip to <?php echo ucwords(strtolower($typ)); ?>
            </button>
        </a>
        | 
        <a href="<?php echo url('editor/pdf?id='.$svg_id.'&type='.$svg_type.'&link='.Session::get('user')->link); ?>" target="_blank" style="text-decoration:none;">
            <button type="button" class="btn btn-danger btn-xs">
                <i class="fa fa-file-pdf-o"></i> Download PDF
            </button>
        </a>

        
    </div>
    
</div>

<div id="tools_top" class="tools_panel">

    <div id="editor_panel">
        <div class="tool_sep"></div>
        <div class="push_button" id="tool_source" title="Edit Source [U]"></div>
        <div class="tool_button" id="tool_wireframe" title="Wireframe Mode [F]"></div>
    </div>

    <!-- History buttons -->
    <div id="history_panel">
        <div class="tool_sep"></div>
        <div class="push_button tool_button_disabled" id="tool_undo" title="Undo [Z]"></div>
        <div class="push_button tool_button_disabled" id="tool_redo" title="Redo [Y]"></div>
    </div>
    
    <!-- Buttons when a single element is selected -->
    <div id="selected_panel">
        <div class="toolset" style="display:none;">
            <div class="tool_sep"></div>
            <!-- <div class="push_button" id="tool_clone" title="Duplicate Element [D]"></div>
            <div class="push_button" id="tool_delete" title="Delete Element [Delete/Backspace]"></div>
            <div class="tool_sep"></div>
            <div class="push_button" id="tool_move_top" title="Bring to Front [ Ctrl+Shift+] ]"></div>
            <div class="push_button" id="tool_move_bottom" title="Send to Back [ Ctrl+Shift+[ ]"></div>
            <div class="push_button" id="tool_topath" title="Convert to Path"></div>
            <div class="push_button" id="tool_reorient" title="Reorient path"></div>
            <div class="push_button" id="tool_make_link" title="Make (hyper)link"></div> -->
            <div class="tool_sep"></div>
            <label id="idLabel" title="Identify the element" style="display:none;">
                <span>id:</span>
                <input id="elem_id" class="attr_changer" data-attr="id" size="10" type="text"/>
            </label>
        </div>

       <!--  <label id="tool_angle" title="Change rotation angle" class="toolset">
            <span id="angleLabel" class="icon_label"></span>
            <input id="angle" size="2" value="0" type="text"/>
        </label>
        
        <div class="toolset" id="tool_blur" title="Change gaussian blur value">
            <label>
                <span id="blurLabel" class="icon_label"></span>
                <input id="blur" size="2" value="0" type="text"/>
            </label>
            <div id="blur_dropdown" class="dropdown">
                <button type="button" ></button>
                <ul>
                    <li class="special"><div id="blur_slider"></div></li>
                </ul>
            </div>
        </div> -->
        
        <!-- <div class="dropdown toolset" id="tool_position" title="Align Element to Page" style="margin-left:40px;">
                <div id="cur_position" class="icon_label"></div>
                <button type="button"></button>
        </div> -->     

        <!-- <div id="xy_panel" class="toolset">
            <label>
                x: <input id="selected_x" class="attr_changer" title="Change X coordinate" size="3" data-attr="x"/>
            </label>
            <label>
                y: <input id="selected_y" class="attr_changer" title="Change Y coordinate" size="3" data-attr="y"/>
            </label>
        </div> -->
    </div>
    
    <!-- Buttons when multiple elements are selected -->
    <div id="multiselected_panel">
        <div class="tool_sep"></div>
        <!-- <div class="push_button" id="tool_clone_multi" title="Clone Elements [C]"></div>
        <div class="push_button" id="tool_delete_multi" title="Delete Selected Elements [Delete/Backspace]"></div> -->
        <div class="tool_sep"></div>
        <div class="push_button" id="tool_group_elements" title="Group Elements [G]" style="margin-left:45px;"></div>
        <!-- <div class="push_button" id="tool_make_link_multi" title="Make (hyper)link"></div> -->
        <div class="push_button" id="tool_alignleft" title="Align Left"></div>
        <div class="push_button" id="tool_aligncenter" title="Align Center"></div>
        <div class="push_button" id="tool_alignright" title="Align Right"></div>
        <div class="push_button" id="tool_aligntop" title="Align Top"></div>
        <div class="push_button" id="tool_alignmiddle" title="Align Middle"></div>
        <div class="push_button" id="tool_alignbottom" title="Align Bottom"></div>
        <label id="tool_align_relative"> 
            <span id="relativeToLabel">relative to:</span>
            <select id="align_relative_to" title="Align relative to ...">
            <option id="selected_objects" value="selected">selected objects</option>
            <option id="largest_object" value="largest">largest object</option>
            <option id="smallest_object" value="smallest">smallest object</option>
            <option id="page" value="page">page</option>
            </select>
        </label>
        <div class="tool_sep"></div>

    </div>

    <div id="rect_panel">
        <div class="toolset">
            <label id="rect_width_tool" title="Change rectangle width">
                <span id="rwidthLabel" class="icon_label"></span>
                <input id="rect_width" class="attr_changer" size="3" data-attr="width"/>
            </label>
            <label id="rect_height_tool" title="Change rectangle height">
                <span id="rheightLabel" class="icon_label"></span>
                <input id="rect_height" class="attr_changer" size="3" data-attr="height"/>
            </label>
        </div>
        <label id="cornerRadiusLabel" title="Change Rectangle Corner Radius" class="toolset">
            <span class="icon_label"></span>
            <input id="rect_rx" size="3" value="0" type="text" data-attr="Corner Radius"/>
        </label>
    </div>

    <div id="image_panel">
    <div class="toolset" style="display:none;">
        <label><span id="iwidthLabel" class="icon_label"></span>
        <input id="image_width" class="attr_changer" title="Change image width" size="3" data-attr="width"/>
        </label>
        <label><span id="iheightLabel" class="icon_label"></span>
        <input id="image_height" class="attr_changer" title="Change image height" size="3" data-attr="height"/>
        </label>
    </div>
    <!-- <div class="toolset">
        <label id="tool_image_url">url:
            <input id="image_url" type="text" title="Change URL" size="35"/>
        </label>
        <label id="tool_change_image">
            <button type="button" id="change_image_url" style="display:none;">Change Image</button>
            <span id="url_notice" title="NOTE: This image cannot be embedded. It will depend on this path to be displayed"></span>
        </label>
    </div> -->
    <div class="toolset changeimage">
        <button type="button" id="change_image" class="btn btn-primary btn-xs" style="margin-top:5px;">
            Change Image
        </button>
    </div>
    <div class="toolset imageprice">
        <button type="button" id="image_price" class="btn btn-success btn-xs" style="margin-top:5px;">
            Price: Free
        </button>
    </div>
  </div>

    <div id="circle_panel">
        <div class="toolset">
            <label id="tool_circle_cx">cx:
            <input id="circle_cx" class="attr_changer" title="Change circle's cx coordinate" size="3" data-attr="cx"/>
            </label>
            <label id="tool_circle_cy">cy:
            <input id="circle_cy" class="attr_changer" title="Change circle's cy coordinate" size="3" data-attr="cy"/>
            </label>
        </div>
        <div class="toolset">
            <label id="tool_circle_r">r:
            <input id="circle_r" class="attr_changer" title="Change circle's radius" size="3" data-attr="r"/>
            </label>
        </div>
    </div>

    <div id="ellipse_panel">
        <div class="toolset">
            <label id="tool_ellipse_cx">cx:
            <input id="ellipse_cx" class="attr_changer" title="Change ellipse's cx coordinate" size="3" data-attr="cx"/>
            </label>
            <label id="tool_ellipse_cy">cy:
            <input id="ellipse_cy" class="attr_changer" title="Change ellipse's cy coordinate" size="3" data-attr="cy"/>
            </label>
        </div>
        <div class="toolset">
            <label id="tool_ellipse_rx">rx:
            <input id="ellipse_rx" class="attr_changer" title="Change ellipse's x radius" size="3" data-attr="rx"/>
            </label>
            <label id="tool_ellipse_ry">ry:
            <input id="ellipse_ry" class="attr_changer" title="Change ellipse's y radius" size="3" data-attr="ry"/>
            </label>
        </div>
    </div>

    <div id="line_panel">
        <div class="toolset">
            <label id="tool_line_x1">x1:
            <input id="line_x1" class="attr_changer" title="Change line's starting x coordinate" size="3" data-attr="x1"/>
            </label>
            <label id="tool_line_y1">y1:
            <input id="line_y1" class="attr_changer" title="Change line's starting y coordinate" size="3" data-attr="y1"/>
            </label>
        </div>
        <div class="toolset">
            <label id="tool_line_x2">x2:
            <input id="line_x2" class="attr_changer" title="Change line's ending x coordinate" size="3" data-attr="x2"/>
            </label>
            <label id="tool_line_y2">y2:
            <input id="line_y2" class="attr_changer" title="Change line's ending y coordinate" size="3" data-attr="y2"/>
            </label>
        </div>
    </div>

    <div id="text_panel">
        <div class="toolset" style="margin-left:52px;">
            <div class="tool_button" id="tool_bold" title="Bold Text [B]"><span></span>B</div>
            <div class="tool_button" id="tool_italic" title="Italic Text [I]"><span></span>i</div>
        </div>
        
        <div class="toolset" id="tool_font_family">
            <label>
                <!-- Font family -->
                <input id="font_family" type="text" title="Change Font Family" size="12"/>
            </label>
            <div id="font_family_dropdown" class="dropdown">
                <button type="button" ></button>
                <ul>
                    <li style="font-family:serif">Serif</li>
                    <li style="font-family:sans-serif">Sans-serif</li>
                    <li style="font-family:cursive">Cursive</li>
                    <li style="font-family:fantasy">Fantasy</li>
                    <li style="font-family:monospace">Monospace</li>
                </ul>
            </div>
        </div>

        <label id="tool_font_size" title="Change Font Size">
            <span id="font_sizeLabel" class="icon_label"></span>
            <input id="font_size" size="3" value="0" type="text"/>
        </label>
        
        <!-- Not visible, but still used -->
        <input id="text" type="text" size="35"/>
        <div id="color_tools">
            <div class="color_tool" id="tool_fill">
                <label class="icon_label" for="fill_color" title="Change fill color"></label>
                <div class="color_block">
                    <div id="fill_bg"></div>
                    <div id="fill_color" class="color_block"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- formerly gsvg_panel -->
    <div id="container_panel">
        <div class="tool_sep"></div>

        <!-- Add viewBox field here? -->

        <label id="group_title" title="Group identification label">
            <span>label:</span>
            <input id="g_title" data-attr="title" size="10" type="text"/>
        </label>
    </div>
    
    <div id="use_panel">
        <div class="push_button" id="tool_unlink_use" title="Break link to reference element (make unique)"></div>
    </div>
    
    <div id="g_panel">
        <div class="push_button" id="tool_ungroup" title="Ungroup Elements [G]"></div>
    </div>

    <!-- For anchor elements -->
    <div id="a_panel">
        <label id="tool_link_url" title="Set link URL (leave empty to remove)">
            <span id="linkLabel" class="icon_label"></span>
            <input id="link_url" type="text" size="35"/>
        </label>    
    </div>
    
    <div id="path_node_panel">
        <div class="tool_sep"></div>
        <div class="tool_button push_button_pressed" id="tool_node_link" title="Link Control Points"></div>
        <div class="tool_sep"></div>
        <label id="tool_node_x">x:
            <input id="path_node_x" class="attr_changer" title="Change node's x coordinate" size="3" data-attr="x"/>
        </label>
        <label id="tool_node_y">y:
            <input id="path_node_y" class="attr_changer" title="Change node's y coordinate" size="3" data-attr="y"/>
        </label>
        
        <select id="seg_type" title="Change Segment type">
            <option id="straight_segments" selected="selected" value="4">Straight</option>
            <option id="curve_segments" value="6">Curve</option>
        </select>
        <div class="tool_button" id="tool_node_clone" title="Clone Node"></div>
        <div class="tool_button" id="tool_node_delete" title="Delete Node"></div>
        <div class="tool_button" id="tool_openclose_path" title="Open/close sub-path"></div>
        <div class="tool_button" id="tool_add_subpath" title="Add sub-path"></div>
    </div>
</div> <!-- tools_top -->
    <div id="cur_context_panel">
        
    </div>


<div id="tools_left" class="tools_panel">
    <!-- <div class="tool_button" id="tool_select" title="Select Tool"></div> -->
    <!-- <div class="tool_button" id="tool_fhpath" title="Pencil Tool"></div>
    <div class="tool_button" id="tool_line" title="Line Tool"></div>
    <div class="tool_button flyout_current" id="tools_rect_show" title="Square/Rect Tool">
        <div class="flyout_arrow_horiz"></div>
    </div>
    <div class="tool_button flyout_current" id="tools_ellipse_show" title="Ellipse/Circle Tool">
        <div class="flyout_arrow_horiz"></div>
    </div>
    <div class="tool_button" id="tool_path" title="Path Tool"></div> -->
    <!-- <div class="tool_button" id="tool_text" title="Text Tool"></div> -->
   <!--  <div class="tool_button" id="tool_image" title="Image Tool"></div>
    <div class="tool_button" id="tool_zoom" title="Zoom Tool [Ctrl+Up/Down]"></div>
    
    <div style="display: none">
        <div id="tool_rect" title="Rectangle"></div>
        <div id="tool_square" title="Square"></div>
        <div id="tool_fhrect" title="Free-Hand Rectangle"></div>
        <div id="tool_ellipse" title="Ellipse"></div>
        <div id="tool_circle" title="Circle"></div>
        <div id="tool_fhellipse" title="Free-Hand Ellipse"></div>
    </div> -->
</div> <!-- tools_left -->

<div id="tools_bottom" class="tools_panel">

    <!-- Zoom buttons -->
    <div id="zoom_panel" class="toolset" title="Change zoom level">
        <label>
        <span id="zoomLabel" class="zoom_tool icon_label"></span>
        <input id="zoom" size="3" value="100" type="text" />
        </label>
        <div id="zoom_dropdown" class="dropdown">
            <button type="button" ></button>
            <ul>
                <li>1000%</li>
                <li>400%</li>
                <li>200%</li>
                <li>100%</li>
                <li>50%</li>
                <li>25%</li>
                <li id="fit_to_canvas" data-val="canvas">Fit to canvas</li>
                <li id="fit_to_sel" data-val="selection">Fit to selection</li>
                <li id="fit_to_layer_content" data-val="layer">Fit to layer content</li>
                <li id="fit_to_all" data-val="content">Fit to all content</li>
                <li>100%</li>
            </ul>
        </div>
        <div class="tool_sep"></div>
    </div>

    <div id="tools_bottom_2" style="display:none;">
        <div id="color_tools">
            
            <div class="color_tool" id="tool_fill">
                <label class="icon_label" for="fill_color" title="Change fill color"></label>
                <div class="color_block">
                    <div id="fill_bg"></div>
                    <div id="fill_color" class="color_block"></div>
                </div>
            </div>
        
            <div class="color_tool" id="tool_stroke">
                <label class="icon_label" title="Change stroke color"></label>
                <div class="color_block">
                    <div id="stroke_bg"></div>
                    <div id="stroke_color" class="color_block" title="Change stroke color"></div>
                </div>
                
                <label class="stroke_label">
                    <input id="stroke_width" title="Change stroke width by 1, shift-click to change by 0.1" size="2" value="5" type="text" data-attr="Stroke Width"/>
                </label>

                <div id="toggle_stroke_tools" title="Show/hide more stroke tools"></div>
                
                <label class="stroke_tool">
                    <select id="stroke_style" title="Change stroke dash style">
                        <option selected="selected" value="none">&#8212;</option>
                        <option value="2,2">...</option>
                        <option value="5,5">- -</option>
                        <option value="5,2,2,2">- .</option>
                        <option value="5,2,2,2,2,2">- ..</option>
                    </select>
                </label>

                <div class="stroke_tool dropdown" id="stroke_linejoin">
                    <div id="cur_linejoin" title="Linejoin: Miter"></div>
                    <button type="button" ></button>
                </div>
                
                <div class="stroke_tool dropdown" id="stroke_linecap">
                    <div id="cur_linecap" title="Linecap: Butt"></div>
                    <button type="button" ></button>
                </div>

            </div>

            <div class="color_tool" id="tool_opacity" title="Change selected item opacity">
                <label>
                    <span id="group_opacityLabel" class="icon_label"></span>
                    <input id="group_opacity" size="3" value="100" type="text"/>
                </label>
                <div id="opacity_dropdown" class="dropdown">
                    <button type="button" ></button>
                    <ul>
                        <li>0%</li>
                        <li>25%</li>
                        <li>50%</li>
                        <li>75%</li>
                        <li>100%</li>
                        <li class="special"><div id="opac_slider"></div></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- <div id="tools_bottom_3">
        <div id="palette_holder"><div id="palette" title="Click to change fill color, shift-click to change stroke color"></div></div>
    </div> -->
    <!-- <div id="copyright"><span id="copyrightLabel">Powered by</span> <a href="http://svg-edit.googlecode.com/" target="_blank">SVG-edit v2.6-beta</a></div> -->
</div>

<div id="option_lists" class="dropdown">
    <ul id="linejoin_opts">
        <li class="tool_button current" id="linejoin_miter" title="Linejoin: Miter"></li>
        <li class="tool_button" id="linejoin_round" title="Linejoin: Round"></li>
        <li class="tool_button" id="linejoin_bevel" title="Linejoin: Bevel"></li>
    </ul>
    
    <ul id="linecap_opts">
        <li class="tool_button current" id="linecap_butt" title="Linecap: Butt"></li>
        <li class="tool_button" id="linecap_square" title="Linecap: Square"></li>
        <li class="tool_button" id="linecap_round" title="Linecap: Round"></li>
    </ul>
    
    <ul id="position_opts" class="optcols3">
        <li class="push_button" id="tool_posleft" title="Align Left"></li>
        <li class="push_button" id="tool_poscenter" title="Align Center"></li>
        <li class="push_button" id="tool_posright" title="Align Right"></li>
        <li class="push_button" id="tool_postop" title="Align Top"></li>
        <li class="push_button" id="tool_posmiddle" title="Align Middle"></li>
        <li class="push_button" id="tool_posbottom" title="Align Bottom"></li>
    </ul>
</div>


<!-- hidden divs -->
<div id="color_picker"></div>

</div> <!-- svg_editor -->

<div id="svg_source_editor">
    <div class="overlay"></div>
    <div id="svg_source_container">
        <div id="tool_source_back" class="toolbar_button">
            <button type="button"  id="tool_source_save">Apply Changes</button>
            <button type="button"  id="tool_source_cancel">Cancel</button>
        </div>
        <div id="save_output_btns">
            <p id="copy_save_note">Copy the contents of this box into a text editor, then save the file with a .svg extension.</p>
            <button type="button"  id="copy_save_done">Done</button>
        </div>
        <form>
            <textarea id="svg_source_textarea" spellcheck="false"></textarea>
        </form>
    </div>
</div>


<div id="svg_docprops">
    <div class="overlay"></div>
    <div id="svg_docprops_container">
        <div id="tool_docprops_back" class="toolbar_button">
            <button type="button"  id="tool_docprops_save">OK</button>
            <button type="button"  id="tool_docprops_cancel">Cancel</button>
        </div>


        <fieldset id="svg_docprops_docprops">
            <legend id="svginfo_image_props">Image Properties</legend>
            <label>
                <span id="svginfo_title">Title:</span>
                <input type="text" id="canvas_title"/>
            </label>

            <fieldset id="change_resolution">
                <legend id="svginfo_dim">Canvas Dimensions</legend>

                <label><span id="svginfo_width">width:</span> <input type="text" id="canvas_width" size="6"/></label>

                <label><span id="svginfo_height">height:</span> <input type="text" id="canvas_height" size="6"/></label>

                <label>
                    <select id="resolution">
                        <option id="selectedPredefined" selected="selected">Select predefined:</option>
                        <option>640x480</option>
                        <option>800x600</option>
                        <option>1024x768</option>
                        <option>1280x960</option>
                        <option>1600x1200</option>
                        <option id="fitToContent" value="content">Fit to Content</option>
                    </select>
                </label>
            </fieldset>

            <fieldset id="image_save_opts">
                <legend id="includedImages">Included Images</legend>
                <label><input type="radio" name="image_opt" value="embed" checked="checked"/> <span id="image_opt_embed">Embed data (local files)</span> </label>
                <label><input type="radio" name="image_opt" value="ref"/> <span id="image_opt_ref">Use file reference</span> </label>
            </fieldset>
        </fieldset>

    </div>
</div>

<div id="svg_prefs">
    <div class="overlay"></div>
    <div id="svg_prefs_container">
        <div id="tool_prefs_back" class="toolbar_button">
            <button type="button"  id="tool_prefs_save">OK</button>
            <button type="button"  id="tool_prefs_cancel">Cancel</button>
        </div>

        <fieldset>
            <legend id="svginfo_editor_prefs">Editor Preferences</legend>

            <label><span id="svginfo_lang">Language:</span>
                <!-- Source: http://en.wikipedia.org/wiki/Language_names -->
                <select id="lang_select">
                  <option id="lang_ar" value="ar">العربية</option>
                    <option id="lang_cs" value="cs">Čeština</option>
                    <option id="lang_de" value="de">Deutsch</option>
                    <option id="lang_en" value="en" selected="selected">English</option>
                    <option id="lang_es" value="es">Español</option>
                    <option id="lang_fa" value="fa">فارسی</option>
                    <option id="lang_fr" value="fr">Français</option>
                    <option id="lang_fy" value="fy">Frysk</option>
                    <option id="lang_hi" value="hi">&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;, &#2361;&#2367;&#2306;&#2342;&#2368;</option>
                    <option id="lang_it" value="it">Italiano</option>
                    <option id="lang_ja" value="ja">日本語</option>
                    <option id="lang_nl" value="nl">Nederlands</option>
                    <option id="lang_pl" value="pl">Polski</option>
                    <option id="lang_pt-BR" value="pt-BR">Português (BR)</option>
                    <option id="lang_ro" value="ro">Română</option>
                    <option id="lang_ru" value="ru">Русский</option>
                    <option id="lang_sk" value="sk">Slovenčina</option>
                    <option id="lang_zh-TW" value="zh-TW">繁體中文</option>
                </select>
            </label>

            <label><span id="svginfo_icons">Icon size:</span>
                <select id="iconsize">
                    <option id="icon_small" value="s">Small</option>
                    <option id="icon_medium" value="m" selected="selected">Medium</option>
                    <option id="icon_large" value="l">Large</option>
                    <option id="icon_xlarge" value="xl">Extra Large</option>
                </select>
            </label>

            <fieldset id="change_background">
                <legend id="svginfo_change_background">Editor Background</legend>
                <div id="bg_blocks"></div>
                <label><span id="svginfo_bg_url">URL:</span> <input type="text" id="canvas_bg_url"/></label>
                <p id="svginfo_bg_note">Note: Background will not be saved with image.</p>
            </fieldset>

            <fieldset id="change_grid">
                <legend id="svginfo_grid_settings">Grid</legend>
                <label><span id="svginfo_snap_onoff">Snapping on/off</span><input type="checkbox" value="snapping_on" id="grid_snapping_on"/></label>
                <label><span id="svginfo_snap_step">Snapping Step-Size:</span> <input type="text" id="grid_snapping_step" size="3" value="10"/></label>
                <label><span id="svginfo_grid_color">Grid color:</span> <input type="text" id="grid_color" size="3" value="#000"/></label>
            </fieldset>

            <fieldset id="units_rulers">
                <legend id="svginfo_units_rulers">Units &amp; Rulers</legend>
                <label><span id="svginfo_rulers_onoff">Show rulers</span><input type="checkbox" value="show_rulers" id="show_rulers" checked="checked"/></label>
                <label>
                    <span id="svginfo_unit">Base Unit:</span>
                    <select id="base_unit">
                        <option value="px">Pixels</option>
                        <option value="cm">Centimeters</option>
                        <option value="mm">Millimeters</option>
                        <option value="in">Inches</option>
                        <option value="pt">Points</option>
                        <option value="pc">Picas</option>
                        <option value="em">Ems</option>
                        <option value="ex">Exs</option>
                    </select>
                </label>
                <!-- Should this be an export option instead? -->
<!-- 
                <span id="svginfo_unit_system">Unit System:</span>
                <label>
                    <input type="radio" name="unit_system" value="single" checked="checked"/>
                    <span id="svginfo_single_type_unit">Single type unit</span>
                    <small id="svginfo_single_type_unit_sub">CSS unit type is set on root element. If a different unit type is entered in a text field, it is converted back to user units on export.</small>
                </label>
                <label>
                    <input type="radio" name="unit_system" value="multi"/>
                    <span id="svginfo_multi_units">Multiple CSS units</span> 
                    <small id="svginfo_single_type_unit_sub">Attributes can be given different CSS units, which may lead to inconsistant results among viewers.</small>
                </label>
 -->
            </fieldset>

        </fieldset>

    </div>
</div>

<div id="dialog_box">
    <div class="overlay"></div>
    <div id="dialog_container">
        <div id="dialog_content"></div>
        <div id="dialog_buttons"></div>
    </div>
</div>

<ul id="cmenu_canvas" class="contextMenu">
    <!-- <li><a href="#cut">Cut</a></li>
    <li><a href="#copy">Copy</a></li>
    <li><a href="#paste">Paste</a></li>
    <li><a href="#paste_in_place">Paste in Place</a></li>
    <li class="separator"><a href="#delete">Delete</a></li> -->
    <li class="separator"><a href="#group">Group<span class="shortcut">G</span></a></li>
    <li><a href="#ungroup">Ungroup<span class="shortcut">G</span></a></li>
    <li class="separator"><a href="#move_front">Bring to Front<span class="shortcut">SHFT+CTRL+]</span></a></li>
    <li><a href="#move_up">Bring Forward<span class="shortcut">CTRL+]</span></a></li>
    <li><a href="#move_down">Send Backward<span class="shortcut">CTRL+[</span></a></li>
    <li><a href="#move_back">Send to Back<span class="shortcut">SHFT+CTRL+[</span></a></li>
</ul>


<ul id="cmenu_layers" class="contextMenu">
    <li><a href="#dupe">Duplicate Layer...</a></li>
    <li><a href="#delete">Delete Layer</a></li>
    <li><a href="#merge_down">Merge Down</a></li>
    <li><a href="#merge_all">Merge All</a></li>
</ul>


</div>


<link href="{{ asset('css/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/css/main.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('core/selectize/css/selectize.default.css') }}">

<script src="{{ asset('core/bootstrap.min.js') }}"></script>
<script src="{{ asset('core/json2.js') }}"></script>
<script src="{{ asset('core/underscore.js') }}"></script>
<script src="{{ asset('core/backbone.js') }}"></script>
<script src="{{ asset('core/backbone.marionette.js') }}"></script>

<script src="{{ asset('core/dropzone.js') }}"></script>
<script src="{{ asset('core/wookmark.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.1.8/imagesloaded.pkgd.min.js"></script>



<div class="row">

        <div class="col-md-12">

            <div id="main-region" class="col-md-12">

               

                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="overlay" style="display: none;">
                            <div class="loading_view">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                        </div>
                      <div class="modal-header" id="modal-header_wrap">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Replace Selected Image</h4>
                        <hr>
                         <div class="row" id="top_region">
                            
                        </div>
                        
                        <div class="clearfix"></div>
                      </div>
                      <div class="modal-body">
                       
    
                        <div class="tab-content">

    <!-- Saved Photos -->                   
                          <div role="tabpanel" class="tab-pane saved_photos" id="saved_photos">
                            <div class="list-group">    
                                <div id="savedphotos">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                          </div>

    <!-- Computer -->   
                          <div role="tabpanel" class="tab-pane computer" id="computer">
                            <div class="list-group">
                                <input type="hidden" value="<?php echo url('uploadFile?link='.Session::get('user')->link); ?>" id="upload_url">
                                    
                                <div id="actions" class="row">

                                  <div class="col-lg-7">
                                    <span class="btn btn-success btn-xs fileinput-button dz-clickable">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Add files...</span>
                                    </span>
                                    <button type="submit" class="btn btn-primary btn-xs start" style="display:none;">
                                        <i class="glyphicon glyphicon-upload"></i>
                                        <span>Start upload</span>
                                    </button>
                                    <button type="reset" class="btn btn-warning btn-xs cancel">
                                        <i class="glyphicon glyphicon-refresh"></i>
                                        <span>Clear</span>
                                    </button>
                                  </div>

                                  <div class="col-lg-5">
                                    <!-- The global file processing state -->
                                    <span class="fileupload-process">
                                      <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress=""></div>
                                      </div>
                                    </span>
                                  </div>

                                </div>

                                <div class="table table-striped" class="files" id="previews">
                                 
                                  <div id="template" class="file-row">
                                    <div>
                                        <span class="preview">
                                            <img data-dz-thumbnail height="100" width="100"/>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="name" data-dz-name></p>
                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                    </div>
                                    <div>
                                        <p class="size" data-dz-size></p>
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                          <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                    <div>
                                      <button class="btn btn-primary btn-xs start">
                                          <i class="glyphicon glyphicon-upload"></i>
                                          <span>Start</span>
                                      </button>
                                      <button data-dz-remove class="btn btn-warning btn-xs cancel">
                                          <i class="glyphicon glyphicon-ban-circle"></i>
                                          <span>Cancel</span>
                                      </button>
                                      <button data-dz-remove class="btn btn-success btn-xs delete">
                                        <i class="glyphicon glyphicon-ok"></i>
                                        <span>Successfully Uploaded</span>
                                      </button>
                                    </div>
                                  </div>
                                         

    
                                </div>
                                <div class="clearfix"></div>
                            </div>
                          </div>

    <!-- Buy Photos -->
                           <div role="tabpanel" class="tab-pane buy_photos" id="buy_photos">
                            <div class="list-group">    
                                <div class="col-md-12" id="search_region">
                                    
                                </div>
                                <div class="clearfix"></div>
                            </div>
                          </div>

    <!-- Facebook -->
                          <div role="tabpanel" class="tab-pane facebook" id="facebook">
                            <div class="list-group">
                               <div class="col-md-12">
                                    Facebook
                                </div>
                                <div class="clearfix"></div>
                            </div>
                          </div>

    <!-- Instagram -->
                           <div role="tabpanel" class="tab-pane instagram" id="instagram">
                            <div class="list-group">    
                                <div class="col-md-12">
                                    Instagram
                                </div>
                                <div class="clearfix"></div>
                            </div>
                          </div>

    <!-- Flickr -->
                          <div role="tabpanel" class="tab-pane flickr" id="flickr">
                            <div class="list-group">
                              <div class="col-md-12">
                                    Flickr
                                </div>
                                <div class="clearfix"></div>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button> -->
                      </div>
                    </div>
                  </div>
                </div>

            </div>

        </div>

    </div>

    <script type="text/template" id="top_template">
        <ul class="nav nav-tabs" role="tablist" id="myTab">
            <% _.each(items, function(item){ %>
                <li role="presentation" class="<%= item.slug %>">
                    <a href="#<%= item.slug %>" aria-controls="<%= item.slug %>" role="tab" data-toggle="tab" id="<%= item.slug %>"><%= item.name %></a>
                </li>
            <% }); %>
        </ul>
    </script>

    <script type="text/template" id="saved_template">
        <ul id="container" class="tiles-wrap">
        <% _.each(items, function(item){ %>
            <li>
                <img class="img-responsive" src="photoimg/<%= item.id %><%= link() %>" id="<%= item.id %>">
            </li>
        <% }); %>
        </ul>
    </script>

    <script type="text/template" id="search_template">
        <div class="col-lg-4">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search for..." id="searchval">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" id="search">Go!</button>
              </span>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="fotolia_wrapper"></div>
    </script>

    <script type="text/template" id="fotolia_template">
        <ul id="container2" class="tiles-wrap">
        <% _.each(items, function(item){ %>
            <li>
                <img class="img-responsive" src="<%= item.thumbnail_160_url %>" id="<%= item.id %>">
                <div class="modal fade" id="info_<%= item.id %>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><%= item.title %></h4>
                      </div>
                      <div class="modal-body">
                        <div class="col-md-4">
                            <table class="table table-striped table-bordered table-hover">
                                <% _.each(item.licenses, function(license){ %>
                                    <tr>
                                        <td>
                                            <%= license.name %>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-xs" id="<%= item.id %>-<%= license.name %>" data-src="<%= item.thumbnail_160_url %>">
                                                $ <%= license.price %> 
                                            </button>
                                        </td>
                                    </tr>
                                <% }); %>
                            </table>
                        </div>
                        <div class="col-md-8">
                            <img class="img-responsive" src="<%= item.thumbnail_400_url %>">
                        </div>
                        
                        <div class="clearfix"></div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-xs" data-id="info_<%= item.id %>" id="infoClose">Close</button>
                        <button type="button" class="btn btn-primary btn-xs" id="insert_image" data-id="<%= item.id %>" data-price="<%= item.licenses[0].price %>" data-license="<%= item.licenses[0].name %>" data-src="<%= item.thumbnail_400_url %>">Insert Image
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
            </li>
        <% }); %>
        </ul>
    </script>


    <script src="{{ asset('js/lib.js') }}"></script>


    <script type="text/javascript">
        libs.start();
    </script>   

    <script src="{{ asset('lib/svg-edit/select.js') }}"></script>
    <script type="text/javascript">
        $(function(){
             <?php 
                if($images){ 
                    foreach($images as $item){
                        ?>
                            libs.infos['<?php echo $item->svg_id; ?>'] = <?php echo $item->mediaInfo; ?>;
                            
                        <?php  
                    }
                } 
            ?>

            $('#font_family_dropdown').click(function(){
                $('#font_family_dropdown-list').hide();
                $('#font_family_dropdown-list').fadeIn('slow');
                var getBL = parseInt($('#svgImageLeft').val()) + parseInt(43);
                var getBT = parseInt($('#svgImageTop').val()) - parseInt(30);

                $('#font_family_dropdown-list').css('left', getBL+"px");
                $('#font_family_dropdown-list').css('top', getBT+"px");
            });

        });

        
       
    </script>

</body>
</html>

