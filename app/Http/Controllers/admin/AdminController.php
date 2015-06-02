<?php namespace App\Http\Controllers\admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Cocur\Slugify\Slugify;
use Rhumsaa\Uuid\Uuid;
use Sentry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\NewTemplateRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use DB;
use App\Model\Category;
use App\Model\Template;
use App\Model\Tag;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Session;
use Imagick;


class AdminController extends Controller {

	public $mCategory;
	public $mTemplate;
	public $mTag;

	public function __construct(Category $mCategory, Template $mTemplate, Tag $mTag){
		$this->mTemplate = $mTemplate;
		$this->mCategory = $mCategory;
		$this->mTag = $mTag;
		$this->slug = new Slugify();

		//$this->middleware('guest');
		
	}

	public function index(){
	    return view('admin.index');
	}

	public function logout(){
		Session::flush();
		return redirect('admin');
	}	

	function templates($slug = null){
		if($slug){
			$cats = $this->mCategory->get();
			$categories = DB::table('templates')
		            ->join('category_template', 'category_template.template_id', '=', 'templates.id')
		            ->join('categories', 'categories.id', '=', 'category_template.category_id')
		            ->select('categories.id')
		            ->where('templates.slug', '=', $slug)
		            ->get();

		    foreach($categories as $cat){
		    	$c[] = $cat->id;
		    }

		    $templates = DB::table('templates')
		            ->where('templates.slug', '=', $slug)
		            ->get();

			//$data = $this->mTemplate->whereSlug($slug)->first();

			$data = array('cat' => $c,
						  'cats' => $cats,
						  'templates' => $templates
						 );
		}else{
			$data = $this->mTemplate->get();
		}
	
		return json_encode($data);
	}

	function categories(){
		$data = $this->mCategory->get();
	
		return json_encode($data);
	}

	function newTemp(){

		$slug = $this->slug->slugify(Input::input('slug'));
		Input::merge(array('slug' => $slug));
		$input = Input::all();
		$rules = array(
			'temp_name' => 'required',
			'slug' => 'required|unique:templates',
			'categories' => 'required',
		    //'file' => 'image|max:10000'
		);
		$validation = Validator::make($input, $rules);

		if ($validation->fails()){
			
			return json_encode(array('status' => 1,
							   		 'errors' => $validation->errors()
							  ));

		}else{

			$folder = date('Y-m-d-his');

			$data = array('uid'			=> Uuid::uuid4()->toString(),
						  'temp_name' 	=> Input::input('temp_name'),
					      'temp_desc' 	=> Input::input('temp_desc'),
					      'slug' 		=> $this->slug->slugify(Input::input('slug')),
					      'folder'		=> $folder,
					      'backsvg' 	=> Input::input('back'),
					      'frontsvg' 	=> Input::input('front'),
					      'backjpg' 	=> Input::input('backjpg'),
					      'frontjpg' 	=> Input::input('frontjpg')
					);

			$temp = $this->mTemplate->create($data);

			$cat_ids = json_decode(Input::input('categories'));
			$temp = $temp->categories()->attach($cat_ids);

			$f = storage_path('uploads/temp/'.Input::input('frontFolder'));
			$b = storage_path('uploads/temp/'.Input::input('backFolder'));

			
			$path = storage_path('uploads/templates/');
			@mkdir($path.$folder, 0777, true);

			$target = storage_path('uploads/templates/'.$folder.'/');
			$front = File::allFiles($f);
			$back = File::allFiles($b);

			if($front){
				foreach($front as $f) {
					$path = $f->getPathname();
					$file = $f->getFilename();
					File::move($path, $target.$file);
				}
			}

			if($back){
				foreach($back as $f) {
					$path = $f->getPathname();
					$file = $f->getFilename();
					File::move($path, $target.$file);
				}
			}

			File::cleanDirectory(storage_path('uploads/temp/'));


			return json_encode(array('status' => 0));
		}
	}

	function updateTemp(){
 
		$input = Input::all();

		$rules = array(
			'temp_name' => 'required',
			//'slug' => 'required|unique:templates',
			'categories' => 'required',
		    //'file' => 'image|max:10000'
		);
		$validation = Validator::make($input, $rules);

		if ($validation->fails()){
			
			return json_encode(array('status' => 1,
							   		 'errors' => $validation->errors()
							  ));

		}else{

			$data = array('temp_name' 	=> Input::input('temp_name'),
					      'temp_desc' 	=> Input::input('temp_desc'),
					      'backsvg' 	=> Input::input('back'),
					      'frontsvg' 	=> Input::input('front'),
					      'backjpg' 	=> Input::input('backjpg'),
					      'frontjpg' 	=> Input::input('frontjpg')
					);

			DB::table('templates')
	            ->where('id', Input::get('tempid'))
	            ->update($data);

			$this->mTag->where('template_id', '=', Input::get('tempid'))->delete();

			$cat_ids = json_decode(Input::input('categories'));

			foreach($cat_ids as $catid){
				DB::table('category_template')->insert(
				    array('category_id' => $catid, 'template_id' => Input::get('tempid'))
				);
			}

			return json_encode(array('status' => 0));
		}
	}

	function uploadFile($type){

		$file = Input::file('file');
		$folder = sha1(time().time());
		$destinationPath = storage_path('uploads/temp/'.$folder);
		
		$filename = str_random(12);

		//$filename = $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension(); 

		$filename = sha1(time().time()).".{$extension}";

		$upload_success = Input::file('file')->move($destinationPath, $filename);

		if( $upload_success ) {
			$source = $filename;
			$jpg = $this->convertSVG($source, $folder);
        	return Response::json(array('status' 	=> 0,
        								'filename' 	=> $filename,
        								'jpg'		=> $jpg,
        								'type' 		=> $type,
        								'folder'	=> $folder
        		));
        } else {
        	return Response::json(array('status' => 1));
        }
	}


	function convertSVG($source, $folder){
		$im = new Imagick();

		$image = storage_path('uploads/temp/'.$folder.'/'.$source);
		
		$svg = file_get_contents($image);

		$im->readImageBlob($svg);
		$im->setImageFormat("png24");
		$im->setImageFormat("jpeg");
		//$im->adaptiveResizeImage(1024, 568);

		$filename = sha1(time().time()).".jpg";

		$im->writeImage(storage_path('uploads/temp/'.$folder.'/'.$filename));
		//$im->writeImage(storage_path('uploads/templates/Bakery_bc-01-01.jpg'));
		$im->clear();
		$im->destroy();

		return $filename;
	}

	function delTemp(){
		$temp = $this->mTemplate->find(Input::get('tempid'));

		$path = storage_path('uploads/templates/'.$temp->folder);

		// unlink($path.$temp->backsvg);
		// unlink($path.$temp->frontsvg);
		// unlink($path.$temp->backjpg);
		// unlink($path.$temp->frontjpg);

		File::deleteDirectory($path, $preserve = false);

		$this->mTag->where('template_id', '=', Input::get('tempid'))->delete();
		$this->mTemplate->where('id', '=', Input::get('tempid'))->delete();
		return Response::json(array('status' => 0));
	}
	

	function image($type, $id, $preview = null){

		$temp = DB::table('templates')
			            ->where('id', $id)
			            ->first();

		$request = new Request();
		$folder = ($type == "mTemplate") ? 'uploads/templates/'.$temp->folder.'/': 'uploads/';

		if(isset($preview)){
			$mime = 'image/jpg';
			if($preview == 'back'){
				$img = $this->$type->find($id)->backjpg;
			}else{
				$img = $this->$type->find($id)->frontjpg;
			}

		}else{
			
			if(empty($img)){
				$img = 'default_img.gif';
				$mime = 'image/gif';
			}
			
		}

	    $path = storage_path($folder.$img);

	    $size = filesize($path);
	    $file = file_get_contents($path);
	     
	    $headers = [
	        'Content-Type' => $mime,
	        'Content-Length' => $size
	    ];
	     
	    $response = Response::make( $file, 200, $headers );
	     
	    // $filetime = filemtime($path);
	    // $etag = md5($filetime);
	    // $time = date('r', $filetime);
	    // $expires = date('r', $filetime + 3600);
	     
	    // $response->setEtag($etag);
	    // $response->setLastModified(new Carbon($time));
	    // $response->setExpires(new Carbon($expires));
	    // $response->setPublic();
	     
	    if($response->isNotModified($request)) {
	        return $response;
	    } else {
	    $response->prepare($request);
	        return $response;
	    }
	}

	// public function login(){   
	// 	return view('admin.login');
	// }

	// public function auth(){   
	// 	if(Input::get('email') && Input::get('password')){
			
	// 		$res = $this->authenticateUser(Input::get('email'), Input::get('password'));
	// 		if($res['error'] == 'success'){
	// 			return redirect('admin');
	// 		}else{
	// 			$data['error'] = $res['error'];
	// 			return view('admin.login', $data);
	// 		}
	// 	}else{
	// 		$data['error'] = 'Please fill up all the fields!';
	// 		return view('admin.login', $data);
	// 	}
	// }

	// public function logout(){
	// 	Sentry::logout();
	// 	Session::flush();
	// 	return redirect('admin/login');
	// }

	// function authenticateUser($email, $pass){
	// 	try
	// 	{
	// 	    $credentials = array(
	// 	        'email'    => $email,
	// 	        'password' => $pass,
	// 	    );

	// 	    $user = Sentry::authenticate($credentials, false);

	// 	    $this->loginUser($user->id);

	// 	    $error['error'] = 'success';
	// 	}
	// 	catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
	// 	{
	// 	    $error['error'] = 'Login field is required.';
	// 	}
	// 	catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e)
	// 	{
	// 	    $error['error'] = 'Password field is required.';
	// 	}
	// 	catch (\Cartalyst\Sentry\Users\WrongPasswordException $e)
	// 	{
	// 	    $error['error'] = 'Wrong password, try again.';
	// 	}
	// 	catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
	// 	{
	// 	    $error['error'] = 'User was not found.';
	// 	}
	// 	catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
	// 	{
	// 	    $error['error'] = 'User is not activated.';
	// 	}

	// 	return $error;

	// }

	// function loginUser($id){
	// 	try
	// 	{
	// 	    $user = Sentry::findUserById($id);

	// 	    $info = Sentry::login($user, false);

	// 	    $error['error'] = $info;
	// 	}
	// 	catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
	// 	{
	// 	    $error['error'] = 'Login field is required.';
	// 	}
	// 	catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
	// 	{
	// 	    $error['error'] = 'User not found.';
	// 	}
	// 	catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
	// 	{
	// 	    $error['error'] = 'User not activated.';
	// 	}

	// 	return $error;

	// }

	// function createAdminUser(){
	// 	try
	// 	{
	// 	    // Create the admin user
	// 	    $user = Sentry::createUser(array(
	// 	        'email'       => 'master@printarabia.ae',
	// 	        'password'    => 'password',
	// 	        'activated'   => true,
	// 	        'permissions' => array(
	// 	            'user.create' => 1,
	// 	            'user.delete' => 1,
	// 	            'user.view'   => 1,
	// 	            'user.update' => 1,
	// 	        ),
	// 	    ));

	// 	    return $info['info'] = '0';
	// 	}
	// 	catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
	// 	{
	// 	    return $info['info'] = 1; //'Login field is required.';
	// 	}
	// 	catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e)
	// 	{
	// 	    return $info['info'] = 2; //'Password field is required.'
	// 	}
	// 	catch (\Cartalyst\Sentry\Users\UserExistsException $e)
	// 	{
	// 	    return $info['info'] = 3; //'User with this login already exists.';
	// 	}

	// 	return $info;
	// }

	
}
