@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Categories</a> <a href="#" class="current">View Categories</a> </div>
    <h1>Categories</h1>
    @if(Session::has('flash_message_error'))
            <div class="alert alert-error alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{!! session('flash_message_error') !!}</strong>
            </div>
        @endif   
        @if(Session::has('flash_message_success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{!! session('flash_message_success') !!}</strong>
            </div>
        @endif
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Categories</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Category ID</th>
                  <th>Category Name</th>
                  <th>Level</th>
                  <th>Category URL</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              	@foreach($categories as $category)
                <tr class="gradeX">
                  <td class="center">{{ $category->id }}</td>
                  <td class="center">{{ $category->name }}</td>
                  <td>@foreach($parentCategories as $parentCategory)
                                          @if($parentCategory->id != $category->parent_id)
                                              
                                           @else
                                            {{$parentCategory->name}}
                                            @endif
               @endforeach</td> 
                  <td class="center">{{ $category->url }}</td>
                  <td class="center">
                  <a href="{{url('/admin/edit-category/'. $category->id ) }}" class="btn btn-primary btn-mini">Edit</a> 
                  <a rel="{{ $category->id }}" rel1="delete-category" <?php /* href="{{ url('/admin/delete-category/'.$category->id) }}" */?> href="javascript:" class="btn btn-danger btn-mini deleteRecord">Delete</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection