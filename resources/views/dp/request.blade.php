@extends('layouts.master')

@section('title')
	Request | Template Selection
@stop

@section('content')
<div class="col-md-4">
	<!-- <form method="POST" action="http://design.local/access_token">
		<input type="text" class="form-control" name="return_url" value="http://www.printarabia.ae/upload-data">
		<input type="text" class="form-control" name="authorization_token" value="345n2345j2345jk345345">
		<input type="text" class="form-control" name="user_uid" value="uasdf45">
		<input type="text" class="form-control" name="verification_key" value="3452345kj2345kl345n2345kjl234">
		<input type="text" class="form-control" name="currency" value="AED">
		<button class="btn btn-default btn-xs" type="submit">Editor</button>
	</form>

	<form method="POST" action="http://design.local/editor">
		<input type="text" class="form-control" name="authorization_token" value="ab2fb3a1fdc3b3d71f33a362a195917a">
		<input type="text" class="form-control" name="user_uid" value="uasdf45">
		<button class="btn btn-default btn-xs" type="submit">Go to Editor</button>
	</form>
 -->
	<button class="btn btn-default btn-xs" type="button" onclick="goEditor()">Editor</button>
	<button class="btn btn-default btn-xs" type="button" onclick="goDescribe()">Describe</button>
</div>
@stop

<script type="text/javascript">
	function goEditor(){
		$.ajax({
			url: 'http://design.local/access_token/',
			type: 'POST',
			data: {
				return_url: 'http://www.printarabia.ae/upload-data',
				authorization_token: '345n2345j2345jk345345',
				user_uid: 'user12345',
				verification_key: '3452345kj2345kl345n2345kjl234',
				currency: 'AED'
			},
			dataType: 'json',
			crossDomain : true,
			success: function(data){
				window.location = data.url
			},
			error: function(data, response){
				console.log(response);
			}
		});
	}

	function goDescribe(){
		$.ajax({
			url: 'http://design.local/describe/1',
			type: 'POST',
			data: {
				authorization_token: '345n2345j2345jk345345',
				user_uid: 'user12345',
				verification_key: '3452345kj2345kl345n2345kjl234',
				currency: 'AED'
			},
			dataType: 'json',
			crossDomain : true,
			success: function(data){
				console.log(data);
				$.ajax({
					url: 'http://design.local/getdata',
					type: 'POST',
					data: {
						info: data.data
					},
					dataType: 'json',
					crossDomain : true,
					success: function(data){
						console.log(data);
					},
					error: function(data, response){
						console.log(response);
					}
				});
			},
			error: function(data, response){
				console.log(response);
			}
		});
	}
</script>