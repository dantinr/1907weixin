@extends('layouts.admin')

@section('title', '素材管理-添加')

@section('content')
	<h3>渠道管理-添加</h3>
	<form action='{{url("channel/add_do")}}' method="POST" enctype="multipart/form-data">
	  <div class="form-group">
	    <label for="exampleInputEmail1">渠道名称</label>
	    <input type="text" class="form-control" name='channel_name'>
	  </div>
	   
	  <div class="form-group">
	    <label for="exampleInputEmail1">渠道标识</label>
	    <input type="text" class="form-control" name='channel_status'>
	  </div>

	  <button type="submit" class="btn btn-default">添加</button>
	</form>
@endsection