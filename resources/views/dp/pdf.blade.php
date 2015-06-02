
<div class="col-md-4">
	<form method="POST" action="http://design.local/access_token">
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

	<button class="btn btn-default btn-xs" type="button" onclick="goEditor()">Go to Editor</button>
</div>

<img src="">