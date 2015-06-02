@extends('layouts.master')

<link rel="stylesheet" type="text/css" href="{{ asset('css/css/dropzone.css') }}">

@section('title')
	Editor | Admin
@stop

@section('content')

	<div class="row">

      <div class="col-md-12">
		

        <div class="clearfix" style="height:20px;"></div>

        <div id="main-region" class="col-md-12">

            <div class="col-md-12">
                <H1>Editor | Admin <small><a href="{{ url('admin/logout') }}">Logout</a></small></H1>
            </div>

            <div class="col-md-12">
                
                <div class="clearfix" style="height:20px;"></div>

            </div>

            <div class="col-md-3" id="left-sidebar">
                <div class="list-group" id="sidebar_menu">

                </div>
            </div>

            <div class="col-md-9" id="right-sidebar">
            	<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">
				    	<span id="editor_menu"></span>
				    </h3>
				  </div>
				  <div class="panel-body" id="content-template">
				  	<div class="col-md-12 content_forms">

<!----- New Template ---------------------------------------------->
				  		<div class="new_template" style="display:none;" id="new_template_form">
				  			<div class="alert alert-danger" id="errors" style="display:none;"></div>
				  			<form id="newTemplate">
							  <input type="hidden" class="form-control" name="_token" value="{{ csrf_token() }}">
							  <div class="form-group">
							    <input type="text" class="form-control" id="name" placeholder="Name:" name="name">
							  </div>
							  <div class="form-group">
							    <input type="text" class="form-control" id="desc" placeholder="Description:" name="desc">
							  </div>
							  <div class="form-group">
							    <input type="text" class="form-control" id="slug" placeholder="Slug:" name="slug">
							  </div>
							  <div class="form-group">
							    <input type="hidden" class="form-control" id="categories" placeholder="Categories:" name="categories">
							    <div id="catLists">
							    	
							    </div>
							  </div>
							  <input type="hidden" value="" name="backsvg" id="backsvg">
							  <input type="hidden" value="" name="frontsvg" id="frontsvg">
							  <input type="hidden" value="" name="backjpg" id="backjpg">
							  <input type="hidden" value="" name="frontjpg" id="frontjpg">
							  <input type="hidden" value="" name="backsvgFolder" id="backsvgFolder">
							  <input type="hidden" value="" name="frontsvgFolder" id="frontsvgFolder">
							</form>
							<hr/>
							<h5>SVG:</h5>
							<div class="col-md-6">
								<span class="label label-default">BACK</span>
								<form action="{{ url('admin/uploadFile/back') }}" class="dropzone" id="temp"></form>
							</div>
							<div class="col-md-6">
								<span class="label label-default">FRONT</span>
								<form action="{{ url('admin/uploadFile/front') }}" class="dropzone" id="temp"></form>
							</div>
						  	<div class="clearfix"></div>
						  	<div id="newTemp_wrapper">
						  	
						  	</div>
				  		</div>	

<!----- Edit Template ---------------------------------------------->
				  		<div class="edit_template" style="display:none;" id="edit_template_form">
				  			<div id="editTemp_wrapper">
				  				
				  			</div>
							<hr/>
							<h5>SVG:</h5>
							<div class="col-md-6">
								<span class="label label-default">BACK</span>
								<form action="{{ url('admin/uploadFile/editback') }}" class="dropzone" id="temp"></form>
							</div>
							<div class="col-md-6">
								<span class="label label-default">FRONT</span>
								<form action="{{ url('admin/uploadFile/editfront') }}" class="dropzone" id="temp"></form>
							</div>
						  	<div class="clearfix"></div>
						  	<div id="editBut_wrapper">
						  	
						  	</div>
				  		</div>	


				  	</div>
				  	<div class="clear"></div>
				  	<div class="content_wrapper" id="content_wrapper">
				  		
				  	</div>
				  </div>
				</div>
            </div>

        </div>

      </div>

    </div>

<div class="modal fade" id="viewTemp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="viewTempLabel">Template</h4>
      </div>
      <div class="modal-body">
      	<div class="col-md-12">
        	<img src="" id="tempImg" width="100%">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>

    <script type="text/template" id="menu_template">
        
          <% _.each(items, function(item){ %>
            <a href="#<%= item.name %>" class="list-group-item <%= item.name %>" id="<%= item.name %>"><%= item.icon %> <%= item.name %></a>
          <% }); %>

    </script>





 
  <!-- Templates Block -------------------------------------------------------->  

     <script type="text/template" id="temp_template">
    	<a href="#Templates/new" class="btn btn-primary">
    		<i class="fa fa-plus-circle"></i> New Template
    	</a>

    	<div class="clearfix" style="height:15px;"></div>
        
		<table class="table table-striped table-hover table-bordered">
			<tr>
				<th>UID</th>
				<th>Preview</th>
				<th>Template Name</th>
				<th>Action</th>
			</tr>
			<% _.each(items, function(item){ %>
				<tr id="<%= item.id %>">
					<td><%= item.uid %></td>
					<td id="temp_view">
					<button id="<%= item.id %>" class="btn btn-default btn-xs" data-toggle="modal" data-target="#viewTemp" data-image="admin/image/mTemplate/<%= item.id %>/front"><i class="fa fa-eye"></i> Front</button> 
					<button id="<%= item.id %>" class="btn btn-default btn-xs" data-toggle="modal" data-target="#viewTemp" data-image="admin/image/mTemplate/<%= item.id %>/back"><i class="fa fa-eye" data-image="<%= item.backjpg %>"></i> Back</button>
					</td>
					<td>
						<%= item.temp_name %>
					</td>
					<td>
						<a href="#Templates/edit/<%= item.slug %>" class="btn btn-primary btn-xs">
				    		<i class="fa fa-edit"></i> Edit
				    	</a>
						<button data-id="<%= item.id %>" id="tempDel" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete</button> 
					</td>
				</tr>
				
			<% }); %>
		</table>

    </script>

    <script type="text/template" id="newTemp_template">
	  <button type="button" class="btn btn-default" id="newCancel">Cancel</button>
	  <button type="button" class="btn btn-success" id="saveTemp">Save</button>
    </script>


    <script type="text/template" id="catLists_template">
		<div id="<%= wrap() %>">
			<div class="control-group">
				<select id="<%= state() %>" name="state[]" multiple class="demo-default" placeholder="Categories">
					<option value="">Select a category...</option>
					<% _.each(items, function(item){ %>
						<option value="<%= item.id %>" id="<%= item.id %>"><%= item.cat_name %></option>
					<% }); %>
				</select>
			</div>
		</div>
    </script>

    <script type="text/template" id="editTemp_template">
		<div class="alert alert-danger" id="editerrors" style="display:none;"></div>
		<form id="editTemplate">
			<input type="hidden" class="form-control" name="_token" value="{{ csrf_token() }}">
			<% _.each(templates, function(item){ %>
			<input type="hidden" class="form-control" id="tempid" placeholder="" name="tempid" value="<%= item.id %>">
			<div class="form-group">
				<input type="text" class="form-control" id="editname" placeholder="Name:" name="editname" value="<%= item.temp_name %>">
			</div>
			<div class="form-group">
				<input type="text" class="form-control" id="editdesc" placeholder="Description:" name="editdesc" value="<%= item.temp_desc %>">
			</div>
			<div class="form-group">
				<input type="hidden" class="form-control" id="editcategories" placeholder="Edit categories:" name="editcategories" value="">

				<div id="editcatLists">
					
				</div>
			</div>
				<input type="hidden" name="editbacksvg" id="editbacksvg" value="<%= item.backsvg %>">
				<input type="hidden" name="editfrontsvg" id="editfrontsvg" value="<%= item.frontsvg %>">
				<input type="hidden" name="editbackjpg" id="editbackjpg" value="<%= item.backjpg %>">
				<input type="hidden" name="editfrontjpg" id="editfrontjpg" value="<%= item.frontjpg %>">
			<% }); %>
		</form>

		<div id="editwrapper">
			<div class="control-group">
				<select id="editselect-state" name="state[]" multiple class="demo-default" placeholder="Categories">
					<option value="">Select a category...</option>
					<% _.each(cats, function(c){ %>
						<% 
							res = _.indexOf(cat, c.id); 
							if(res != -1){
								sel = 'selected'
							}else{
								sel = ''
							}
						%>
						<option value="<%= c.id %>" id="<%= c.id %>" <%= sel %>><%= c.cat_name %></option>
					<% }); %>
				</select>
			</div>
		</div>

		<div id="editCatLists"></div>
    </script>

    <script type="text/template" id="viewTemp_template">
    	view
    </script>

    <script type="text/template" id="editButTemp_template">
	  <button type="button" class="btn btn-default" id="editCancel">Cancel</button>
	  <button type="button" class="btn btn-success" id="editSave">Save</button>
    </script>








 <!-- Categories Block ------------------------------------------------------------>   

    <script type="text/template" id="cat_template">

    	<a href="#Categories/new" class="btn btn-primary">
    		<i class="fa fa-plus-circle"></i> New Category
    	</a>

    	<div class="clearfix" style="height:15px;"></div>
        
		<table class="table table-striped table-hover table-bordered">
			<tr>
				<th>UID</th>
				<th>Name</th>
				<th>Templates Linked</th>
				<th>Action</th>
			</tr>
			<% _.each(items, function(item){ %>
				<tr>
					<td><%= item.uid %></td>
					<td>
						<%= item.cat_name %>
					</td>
					<td>
						
					</td>
					<td>
						<button data-id="<%= item.id %>" id="catEdit" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</button> 
						<button data-id="<%= item.id %>" id="catDel" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete</a> 
					</td>
				</tr>
				
			<% }); %>
		</table>

    </script>

    <script type="text/template" id="newCat_template">

   		<div class="alert alert-danger" id="errors" style="display:none;"></div>
		<form id="newCategory">
		  <input type="hidden" class="form-control" name="_token" value="{{ csrf_token() }}">
		  <div class="form-group">
		    <input type="text" class="form-control" id="name" placeholder="Name:" name="name">
		  </div>
		  <div class="form-group">
		    <input type="text" class="form-control" id="pos" placeholder="Possibilities:" name="pos">
		  </div>
		  <button type="button" class="btn btn-default" id="newCat">Cancel</button>
			  <button type="button" class="btn btn-success" id="saveTemp">Save</button>
		 </form>

    </script>

    <script src="{{ asset('core/selectize/js/selectize.js') }}"></script>

    <script src="{{ asset('js/admin.js') }}"></script>

   


    <script type="text/javascript">
        admins.start();
    </script>   

@stop