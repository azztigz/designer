<?php namespace App\Http\Controllers\library;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cocur\Slugify\Slugify;
use Rhumsaa\Uuid\Uuid;
use Sentry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\NewTemplateRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Model\Category;
use App\Model\Template;
use App\Model\Photo;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use DB;

class LibraryPageController extends Controller {

	public $mPhoto;

	public function __construct(Photo $mPhoto){
		$this->mPhoto = $mPhoto;
	}

	public function index(){
		return view('dp.libray');
	}

	public function photos(){
		$photos = DB::table('photos')
			            ->where('user_uid', '=', Session::get('user')->user_uid)
			            ->get();
		return json_encode($photos);
	}

	public function uploadFile(){

		$file = Input::file('file');

		$mimeType = Input::file('file')->getMimeType();

		$destinationPath = storage_path('uploads/users/'.Session::get('user')->user_uid.'/saved_photos');
		
		$filename = str_random(12);

		//$filename = $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension(); 

		$filename = sha1(time().time()).".{$extension}";

		$upload_success = Input::file('file')->move($destinationPath, $filename);

		if( $upload_success ) {
			$data = array('uid'			=> Uuid::uuid4()->toString(),
						  'user_uid' 	=> Session::get('user')->user_uid,
					      'image_name' 	=> $file->getClientOriginalName(),
					      'image_path' 	=> $filename,
					      'mime_type'	=> $mimeType
					);

			$temp = $this->mPhoto->create($data);
        	return Response::json(array('status' 	=> 0,
        								'filename' 	=> $filename
        		));
        } else {
        	return Response::json(array('status' => 1));
        }
	}

	function photoimg($id){

		$request = new Request();

		$img = $this->mPhoto->find($id)->image_path;

		$mime = $this->mPhoto->find($id)->mime_type;

	    $path = storage_path('uploads/users/'.Session::get('user')->user_uid.'/saved_photos/'.$img);
	    $size = filesize($path);
	    $file = file_get_contents($path);
	     
	    $headers = [
	        'Content-Type' => $mime,
	        'Content-Length' => $size
	    ];
	     
	    $response = Response::make( $file, 200, $headers );
	     
	    $filetime = filemtime($path);
	    $etag = md5($filetime);
	    $time = date('r', $filetime);
	    $expires = date('r', $filetime + 3600);
	     
	    $response->setEtag($etag);
	    $response->setLastModified(new Carbon($time));
	    $response->setExpires(new Carbon($expires));
	    $response->setPublic();
	     
	    if($response->isNotModified($request)) {
	        return $response;
	    } else {
	    $response->prepare($request);
	        return $response;
	    }
	}

	function watermark($id){

		$request = new Request();

		$path1 = storage_path('uploads/saved_photos/');
		$path2 = storage_path('uploads/');

		$img = Image::make($path1.'9c8f38d5f73b892337e501b01aee0f5f4a0624ab.jpg');
		
		$watermark = Image::make($path2.'preview.png');
		$img->insert($watermark, 'center')->save($path1.'1.jpg');
	    
	}

}