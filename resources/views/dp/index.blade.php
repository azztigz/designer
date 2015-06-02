@extends('layouts.master')

@section('title')
	Editor | Template Selection
@stop

@section('content')
     <input type="hidden" value="<?php echo Session::get('user')->link; ?>" name="link" id="link">
     <input type="hidden" value="<?php echo url(); ?>" name="mainurl" id="mainurl">
     <div class="row">

      <div class="col-md-12">

        <div class="clearfix" style="height:20px;"></div>

        <div id="main-region" class="col-md-12">

            <div class="col-md-12">
                
                <div class="clearfix" style="height:20px;"></div>

            </div>

            <div class="col-md-3" id="left-sidebar">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h1 class="panel-title">
                       EDITOR
                       <small class="pull-right"><a href="<?php echo Session::get('user')->return_url; ?>"><button class="btn btn-primary"><i class="fa fa-arrow-left"></i> Go Back</button></a></small>
                       <br/>Template Selection
                    </h1>
                    <div class="clearfix"></div>
                  </div>
                  <div class="panel-body" style="min-height:500px;">
                      <div class="list-group" id="sidebar_menu">

                      </div>
                      <div class="temp">
                          <div id="collapseExample" style="display:none;">
                          <div class="well" id="catlists">
                              
                            </div>
                          </div>
                      </div> 
                  </div>
                </div> 
            </div>

            

            <div class="col-md-9" id="right-sidebar">

                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">
                        <div class="form-group has-feedback pull-right" style="margin:0px;">
                          <input type="text" class="form-control col-md-6" id="inputSuccess2" aria-describedby="inputSuccess2Status" placeholder="Search here...">
                          <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                          <span id="inputSuccess2Status" class="sr-only">(success)</span>
                        </div>
                    </h3>
                    <div class="clearfix"></div>
                  </div>
                  <div class="panel-body" style="min-height:500px;">
                      <div class="editor_overlay" style="display: block;">
                          <div class="editor_loading_view">
                              <i class="fa fa-spinner fa-spin"></i>
                          </div>
                      </div>
                      <div id="content-template">
                          
                      </div>
                  </div>
                </div>

                 <div id="content-pagination">
                          
                  </div>

                  <input type="hidden" name="cat_id" value="all" id="cat_id">
                  <input type="hidden" name="pages" value="0" id="pages">
               
            </div>

        </div>

      </div>

    </div>

    <div class="modal fade" id="selectTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="preview_wrap">
          
        </div>
      </div>
    </div>
     
    <script type="text/template" id="menu_template">
        
          <% _.each(items, function(item){ %>
            <a href="#<%= item.name %>" class="list-group-item <%= item.name %>" id="<%= item.name %>"><%= item.icon %> <%= item.name %></a>
          <% }); %>

    </script>

    <script type="text/template" id="categories">

        <ul class="nav nav-tabs" role="tablist" id="myTab">
            <li role="presentation" class="active">
                <a href="#categ" aria-controls="categ" role="tab" data-toggle="tab">
                Categories</a>
            </li>
            <!-- <li role="presentation">
                <a href="#filter" aria-controls="filter" role="tab" data-toggle="tab">Filter</a>
            </li> -->
        </ul>

        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="categ">

            <br/>
            <div class="list-group" id="cat_lists">
                <a href="#template/all/all" class="list-group-item active" slug="all" id="all">All</a>
                <% _.each(items, function(item){ %>
                <a href="#template/<%= item.slug %>/<%= item.id %>" class="list-group-item <%= item.cat_name %>" slug="<%= item.slug %>" id="<%= item.slug %>_<%= item.id %>"><%= item.cat_name %></a>
              <% }); %>
            </div>

          </div>
          <div role="tabpanel" class="tab-pane" id="filter">

            <br/>
            <div class="list-group">
                <a href="#color" class="list-group-item active" slug="color" id="all">Color</a>
                <a href="#name" class="list-group-item" slug="name" id="all">Name</a>
            </div>

          </div>
        </div>


        <!-- <div class="text-middle">
            <div class="btn-group" role="group" aria-label="...">
              <button type="button" class="btn btn-default active">Categories</button>
              <button type="button" class="btn btn-default">Filter</button>
            </div>
        </div> -->
        
    </script>

    <script type="text/template" id="cont_template">

        <div class="row" id="content_temp">
           
            <% _.each(items.works, function(item){ %>
                <div class="col-lg-2 col-md-4 col-xs-6 thumb">
                  <span class="label label-default" id="workLabel">ID: <%= item.id %></span>
                  <a class="thumbnail is-loading" id="preview_href">
                      <img class="img-responsive" src="editor/image/mWork/<%= item.id %><%= link() %>" alt="" data-toggle="modal" id="<%= item.id %>" data-target="#selectTemplate" style="max-height:113px">
                  </a>
                  <span id="img_title"><%= item.work_title %></span>
                </div>
            <% }); %>
            
        </div>

    </script>

    <script type="text/template" id="temp_template">

        <div class="row" id="content_temp">
           
            <% _.each(items.templates, function(item){ %>
                <div class="col-lg-2 col-md-4 col-xs-6 thumb">
                  <a class="thumbnail is-loading" id="preview_href">
                      <img class="img-responsive" src="editor/image/mTemplate/<%= item.id %><%= link() %>" alt="" data-toggle="modal" id="<%= item.id %>" data-target="#selectTemplate" style="max-height:113px">
                  </a>
                  <span id="img_title"><%= item.temp_name %></span>
                </div>
            <% }); %>
            
        </div>
        
    </script>

    <script type="text/template" id="pagination_template">

      <div class="row">
            <div class="col-md-12">
               <nav class="pull-right">
                  <ul class="pagination" id="temp_pagination">
                    <!-- <li id="preview_href">
                      <a id="prev" aria-label="Previous" data-page="prev">
                        <span aria-hidden="true">Prev</span>
                      </a>
                    </li> -->
              <% _.each(page, function(item,key){ %>
                <% 
                  if(key == 0){
                    var sel = 'active';
                  }else{
                    var sel = '';
                  }

                %>
                    <li id="preview_href" class="pag<%= item %> <%= sel %>">
                      <a id="<%= item %>" data-page="<%= item %>">
                        <%= key+1 %>
                      </a>
                    </li>
              <% }); %>
                    <!-- <li id="preview_href">
                      <a id="next" aria-label="Next" data-page="next">
                        <span aria-hidden="true">Next</span>
                      </a>
                    </li> -->
                  </ul>
                </nav>
            </div>
        </div>

    </script>

    <script type="text/template" id="preview_template">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Template</h4>
        </div>
        <div class="modal-body">
          <div class="col-md-6">
            <span class="label label-default">Back</span>
            <hr/>
            <img class="img-responsive" src="editor/image/<%= type %>/<%= id %>/back<%= link() %>" alt="" id="goTo" data-id="<%= id %>" data-type="back" data-link="<%= type %>" style="cursor:pointer;">
          </div>
           <div class="col-md-6">
            <span class="label label-default">Front</span>
            <hr/>
            <img class="img-responsive" src="editor/image/<%= type %>/<%= id %>/front<%= link() %>" alt="" id="goTo" data-id="<%= id %>" data-type="front" data-link="<%= type %>" style="cursor:pointer;">
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

    </script>

    <script src="{{ asset('js/app.js') }}"></script>


    <script type="text/javascript">
        apps.start();
    </script>   

@stop
