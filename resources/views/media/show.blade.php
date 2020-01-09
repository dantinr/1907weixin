@extends('layouts.admin')

@section('title', '素材管理-展示')

@section('content')
	<h3>素材管理-展示</h3>
	<table class='table table-hover table-bordered'>
		<tr>
			<td>标题</td>
			<td>格式</td>
			<td>类型</td>
			<td>展示</td>
		</tr>
		@foreach($data as $k =>$v)
		<tr>
			<td>{{$v['media_name']}}</td>
			<td>{{$v['media_format']}}</td>
			<td>{{$v['media_type']}}</td>
			<td>
			@if($v['media_format'] == 'image')
				<img src="\{{$v['media_url']}}"  width="100px">
			@elseif($v['media_format'] == 'voice')
				<audio  src="\{{$v['media_url']}}" controls="controls" width="200px"></audio>
			@elseif($v['media_format'] == 'video')
				<video src="\{{$v['media_url']}}" controls="controls" width="200px"></video>
			@endif
			</td>
		</tr>
		@endforeach
	</table>
@endsection