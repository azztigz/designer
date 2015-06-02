@extends('layouts.master')

@section('title')
	Wireframe | Template Library
@stop

<link rel="stylesheet" type="text/css" href="{{ asset('css/library/dropzone.css') }}">

@section('content')

	<div class="row">

		<div class="col-md-12">

			<div class="clearfix" style="height:20px;"></div>

			<div id="main-region" class="col-md-12">

				<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">
				  Library
				</button>

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
				               	<input type="hidden" value="'{{ url('uploadFile') }}" id="upload_url">
					       			
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
				<img class="img-responsive" src="library/photoimg/<%= item.id %>" id="<%= item.id %>">
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
				<img class="img-responsive" src="<%= item.thumbnail_160_url %>" id="<%= item.id %>" data-toggle="modal" data-target="#info_<%= item.id %>">
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
											<button type="button" class="btn btn-success btn-xs" id="<%= license.name %>">
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

    

@stop



