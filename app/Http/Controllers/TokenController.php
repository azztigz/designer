<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Response;
use Sentry;
use App\Model\User;
use App\Model\Session as Sess;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class TokenController extends Controller {

	public $mUser;
	public $mSession;

	public function __construct(User $mUser, Sess $mSession){
		$this->mUser = $mUser;
		$this->mSession = $mSession;

		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');
	}

	public function index(){

		Session::flush();

		$rules = array(
			'return_url' 			=> 'required|url',
			//'authorization_token' 	=> 'required|alpha_dash',
			'user_uid' 				=> 'required|alpha_dash',
			'verification_key' 		=> 'required|alpha_dash',
			'currency'		        => 'required|exists:currencies,alpha3'
		);

		$validation = Validator::make(Input::all(), $rules);

		if ($validation->fails()){
			return Response::json(array('error'=>$validation->errors()));
		}else{
			$token = md5(uniqid(mt_rand(), true));

			$users = DB::table('users')->where('user_uid', '=', Input::get('user_uid'))->first();
			
			if($users){
				$data = array(
					'authorization_token' 	=> $token
				);

				DB::table('users')
		            ->where('id', $users->id)
		            ->update($data);
			}else{
				
				$data = array(
					'user_uid' 				=> Input::get('user_uid'),
					'email'					=> Input::get('user_uid').'@printarabia.ae',
					'return_url' 			=> Input::get('return_url'),
					'authorization_token' 	=> $token,
					'verification_key' 		=> Input::get('verification_key'),
					'currency' 				=> Input::get('currency'),
					'activated'				=> 1,
					'created_at' 			=> Carbon::now(),
					'updated_at'			=> Carbon::now()
				);

				$this->mUser->create($data);

				$path = storage_path('uploads/users/');
				@mkdir($path.Input::get('user_uid').'/works', 0777, true);
				@mkdir($path.Input::get('user_uid').'/saved_photos', 0777, true);
				@mkdir($path.Input::get('user_uid').'/zip', 0777, true);

			}

			$link = bin2hex(mcrypt_create_iv(128)).strtotime(date('YmdHis'));
			$url = url('editor?link='.$link);


			$sess = DB::table('sessions')->where('user_uid', '=', Input::get('user_uid'))->first();

			if($sess){
				$sessData = array(
					'link' 	=> $link,
					'session_start' => Carbon::now()
				);

				DB::table('sessions')
		            ->where('user_uid', Input::get('user_uid'))
		            ->update($sessData);
			}else{
				$sessData = array(
					'user_uid' 		=> Input::get('user_uid'),
					'link' 			=> $link,
			    	'session_start'	=> Carbon::now()
				);

				$this->mSession->create($sessData);
			}

			
		    return Response::json(array(
		    	'url' 					=> $url,
		    	'authorization_token' 	=> $token,
		    	'user_uid'			 	=> Input::get('user_uid'),
		    	'link'					=> $link
		    ));
		}
		
			
	}

	function describe($work_id){

		$rules = array(
			//'authorization_token' 	=> 'required|alpha_dash',
			'user_uid' 				=> 'required|alpha_dash',
			'verification_key' 		=> 'required|alpha_dash',
			'currency'		        => 'required|exists:currencies,alpha3'
		);

		$validation = Validator::make(Input::all(), $rules);

		//pr(Input::all());

		if ($validation->fails()){
			return Response::json(array('error'=>$validation->errors()));
		}else{
			$work = DB::table('works')
			            ->where('user_uid', '=', Input::get('user_uid'))
			            ->where('id', '=', $work_id)
			            ->first();

			if(@$work){

				$images = DB::table('images')
				            ->where('work_id', '=', $work_id)
				            ->get();

				Crypt::setKey('1234567891234567');

				$encrypted = Crypt::encrypt(json_encode($work));

				return Response::json(array(
			    	'data' 	=> $encrypted,
			    	'key' 	=> '1234567891234567'
			    ));

			}else{
				return Response::json('No result found');
			}


		}
	}

	function getdata(){
		Crypt::setKey('1234567891234567');
		$enc = json_decode(Crypt::decrypt(Input::get('info')));
		pr($enc); exit;
	}

}

