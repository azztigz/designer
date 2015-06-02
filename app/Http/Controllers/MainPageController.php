<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Model\Work;
use App\Model\Category;
use App\Model\Template;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Response;
use Sentry;
use Illuminate\Support\Facades\Session;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Facades\Storage;
use Cocur\Slugify\Slugify;
use Rhumsaa\Uuid\Uuid;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Imagick;
use ZipArchive;
use Dom;
use PDF;

class MainPageController extends Controller {

	public $mCategory;
	public $mTemplate;
	public $mWork;

	public function __construct(Category $mCategory, Template $mTemplate, Work $mWork){
		$this->mCategory = $mCategory;
		$this->mTemplate = $mTemplate;
		$this->mWork = $mWork;

		$this->slug = new Slugify();
	}

	public function index(){
		//$this->createZip('120150409-123046', false);
		return view('dp.index');
	}

	function editor(){
		$id = Input::get('id');

		$type = Input::get('type');
		$page = Input::get('page');

		$data['svg'] = "editor/image/mWork/".$id."/".$type."?editor=1&link=".Session::get('user')->link;
		$data['svg_id'] = $id;
		$data['svg_type'] = $type;
		$data['svg_work_id'] = $id;

		$typ = ($type == 'front') ? 'back': (($type == 'back') ? 'front': $type);

		$data['typ'] = $typ; 

		$data['edit'] = "editor/svgeditor?page=mWork&id=".$id."&type=".$typ."&link=".Session::get('user')->link;

		$data['images'] = DB::table('images')
							->where('work_id', '=', $id)
							->where('type', '=', $type)
							->get();

		//pr($data['images']);
		
		return view('dp.editor', $data);
	}

	function editorCopyTemp(){
		$svg = DB::table('templates')
	            ->where('id', '=', Input::get('tempid'))
	            ->first();
		$folder = Input::get('tempid').date("Ymd-his");
		$src = storage_path('uploads/templates/'.$svg->folder);
		$dst = storage_path('uploads/users/'.Session::get('user')->user_uid.'/works/'.$folder);

		//File::exists($dst);
		//File::deleteDirectory($dst, $preserve = false);
		File::copyDirectory($src, $dst, $options = null);

		$id = DB::table('works')->insertGetId(
		    [
		    	'uid' 		=> Uuid::uuid4()->toString(), 
		    	'user_uid' 	=> Session::get('user')->user_uid,
		    	'work_title'=> $svg->temp_name,
		    	'work_desc' => $svg->temp_desc,
		    	'slug'		=> $svg->slug,
		    	'folder'	=> $folder,
		    	'backsvg'	=> $svg->backsvg,
		    	'frontsvg'	=> $svg->frontsvg,
		    	'backjpg'	=> $svg->backjpg,
		    	'frontjpg'	=> $svg->frontjpg,
		    	'created_at'=> Carbon::now(),
		    	'updated_at'=> Carbon::now()
		    ]
		);
		return Response::json(array('status' => 0,
									'id' => $id
				));
	}

	function editorSaveFile(Request $request){

		$svg = DB::table('works')
            ->where('id', '=', $request->svg_id)
            ->first();

        $path = storage_path('uploads/users/'.Session::get('user')->user_uid.'/works/'.$svg->folder.'/');

        $file = ($request->svg_type == 'back') ? $path.$svg->backsvg: $path.$svg->frontsvg;	
		
		$s = $request->output_svg; 

		if($request->saveType == 'save'){

			$fh = fopen($file, 'w') or die("Can't open file");
			fwrite($fh, $s);
			fclose($fh);

			if($request->get('svg_images')){

				DB::table('images')->where('work_id', '=', $request->get('svg_id'))->delete();

				foreach($request->get('svg_images') as $key => $item){

					DB::table('images')->insert(
					    [
					    	'uid' 				=> Uuid::uuid4()->toString(), 
					    	'work_id' 			=> $request->get('svg_id'),
					    	'fotolia_id'		=> $item['image']['id'],
					    	'svg_id'			=> $key,
					    	'price' 			=> $item['price'],
					    	'url'				=> 'https://www.fotolia.com/id/'.$item['image']['id'],
					    	'license_details'	=> json_encode($item['image']['licenses_details'][$item['license']]),
					    	'type'				=> $request->get('svg_type'),
					    	'license'			=> $item['license'],
					    	'mediaInfo'         => json_encode($item['image']),
					    	'created_at'		=> Carbon::now(),
					    	'updated_at'		=> Carbon::now()
					    ]
					);
					
				}

			}

			// $im = new Imagick();

			// $im->setResolution(144, 144);

			// $im->readImageBlob($s);
			// $im->setImageFormat("png");
			// //$im->setImageFormat("jpeg");

			// $filename = sha1(time().time()).".png";

			// $im->writeImage(storage_path('uploads/users/'.Session::get('user')->user_uid.'/'.$filename));
			// $im->clear();
			// $im->destroy();

		}else{
			$svg = $request->output_svg;
			$im = new Imagick();

			//$im->setSize(1024,768);
			//$im->newImage (1024, 768, "white");
			$im->setResolution(144, 144);

			//$image = storage_path('uploads/templates/'.$source);
			
			//$svg = file_get_contents($image);

			$im->readImageBlob($svg);
			$im->setImageFormat("png");
			$im->setImageFormat("jpeg");

			//$im->adaptiveResizeImage(1024, 568);
			$filename = sha1(time().time()).".png";

			$im->writeImage(storage_path('uploads/'.$filename));
			$im->clear();
			$im->destroy();
		}

		return Response::json('success');
	}

	function createZip($folder, $overwrite = true) {
			$destination = storage_path('uploads/users/'.Session::get('user')->user_uid.'/zip/'.$folder.'.zip');
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}

			$zippath = storage_path('uploads/users/'.Session::get('user')->user_uid.'/works/'.$folder);

			$files = File::allFiles($zippath);

			foreach($files as $file) {
				$zip->addFile($file->getPathname(),$file->getFilename());
			}

			$zip->close();
	}


	function works(){
		if(Input::get('id') == "recent"){
			$date = Carbon::now()->subMinutes(60);
			$limit = $this->mWork->where('updated_at', '>=', $date)->skip(0)->take(Session::get('limit'))->get();

			$works = DB::table('works')
			            ->where('updated_at', '>=', $date)
			            ->where('user_uid', '=', Session::get('user')->user_uid)
			            ->get();

		}else{
			$works = DB::table('works')
			            ->where('user_uid', '=', Session::get('user')->user_uid)
			            ->get();
			$limit = $this->mWork->skip(0)->take(Session::get('limit'))->get();
		}	

		$total = count($works);

		if($total > Session::get('limit')){
			$pages = (int)($total / Session::get('limit'));
			if(($total % Session::get('limit')) == 0){
				$t = $pages; 
			}else{
				$t = $pages + 1;
			}

			for($x = 0; $x < $t; $x++){
				$p[] = json_encode(($x * Session::get('limit')));
			}

		}else{
			$p = 0;
		}

		$data = array("works" => $works,
				      "pages" => $p
			);

		return json_encode($data);
	}

	function categories(){
		if(Input::get('id')){
			if(Input::get('id') == 'all'){
				$temp = $this->mTemplate->get();

				$limit = DB::table('templates')->skip(0)->take(Session::get('limit'))->get();
			}else{
				$temp = DB::table('category_template')
		            ->join('templates', 'category_template.template_id', '=', 'templates.id')
		            ->where('category_id', '=', Input::get('id'))
		            ->get();

		        $limit = DB::table('category_template')
		            ->join('templates', 'category_template.template_id', '=', 'templates.id')
		            ->where('category_id', '=', Input::get('id'))
		            ->skip(0)->take(Session::get('limit'))
		            ->get();
			}

			$total = count($temp);

			if($total > Session::get('limit')){
				$pages = (int)($total / Session::get('limit'));
				if(($total % Session::get('limit')) == 0){
					$t = $pages; 
				}else{
					$t = $pages + 1;
				}

				for($x = 0; $x < $t; $x++){
					$p[] = json_encode(($x * Session::get('limit')));
				}

			}else{
				$p = 0;
			}

			$data = array("templates" => $limit,
					      "pages" => $p
				);

			return json_encode($data);
		}else{
			$cat = $this->mCategory->get();
			return json_encode($cat);
		}
	}

	function tempPage(){
		if(Input::get('id')){
			if(Input::get('id') == 'all'){
				$limit = DB::table('templates')->skip(Input::get('page'))->take(Session::get('limit'))->get();
			}else{
				
		        $limit = DB::table('category_template')
		            ->join('templates', 'category_template.template_id', '=', 'templates.id')
		            ->where('category_id', '=', Input::get('id'))
		            ->skip(Input::get('page'))->take(Session::get('limit'))
		            ->get();
			}

			$data = array("templates" => $limit);

			return json_encode($data);
		}
	}

	function image($type, $id, $preview = null){

		$request = new Request();
		$folder = ($type == "mTemplate") ? 'uploads/templates/': 'uploads/users/'.Session::get('user')->user_uid.'/works/';

		if(isset($preview)){
			if(Input::get('editor') == 1){
				$mime = 'image/svg+xml';
				$prev = ($preview == 'back') ? "backsvg" : "frontsvg";
			}else{
				$mime = 'image/jpeg';
				$prev = ($preview == 'back') ? "backjpg" : "frontjpg";
			}

			$img = $this->$type->find($id)->$prev;

		}else{
			$img = $this->$type->find($id)->frontjpg;
			$mime = 'image/jpeg';
			
			if(empty($img)){
				$img = $this->$type->find($id)->backjpg;
			}
		}

    	$imgfolder = $this->$type->find($id)->folder;
    	$path = storage_path($folder.$imgfolder.'/'.$img);

	    if(File::exists($path)){
	    	$path = $path;
	    }else{
	    	$img = 'default_img.gif';
			$mime = 'image/gif';
	    	$path = storage_path('uploads/'.$img);
	    }

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
	    $response->setPublic();
	     
	    if($response->isNotModified($request)) {
	        return $response;
	    } else {
	    $response->prepare($request);
	        return $response;
	    }
	}

	function editorPdf(){
		// $data['test'] = 'test';
		// $pdf = Dom::loadView('dp.pdf', $data);
		// return $pdf->download('test.pdf');

		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);

		PDF::SetTitle('SVG');

		//$ht = view('dp.pdf');

		//PDF::writeHTML($ht);

		$id = Input::get('id');
		$type = Input::get('type');

		$front = url('editor/image/mWork/'.$id.'/'.$type.'?editor=1&link='.Session::get('user')->link);
		//$back = url('editor/image/mWork/'.$id.'/back?editor=1&link='.Session::get('user')->link);
		//PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);
		PDF::AddPage();
		PDF::SetAutoPageBreak(TRUE, 0);
		//$txt = 'FRONT';
		//PDF::Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);
		PDF::ImageSVG($front, $x='', $y='', $w='', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=true);
		// PDF::AddPage('L');
		// PDF::SetAutoPageBreak(TRUE, 0);
		// //$txt = 'BACK';
		// //PDF::Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);
		// PDF::ImageSVG($back, $x='', $y='', $w='', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=true);

		PDF::Output(date('Y-m-d-h:i:s').'.pdf');

	}

}
