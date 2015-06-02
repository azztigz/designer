<?php namespace App\Http\Middleware\User;

use Closure;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Session as Sess;
use DB;

class UserAuth {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

	public $mUser;
	public $mSession;

	public function __construct(User $mUser, Sess $mSession){
		$this->mUser = $mUser;
		$this->mSession = $mSession;
	}

	public function handle($request, Closure $next)
	{
		$sess = DB::table('sessions')
					->leftJoin('users', 'users.user_uid', '=', 'sessions.user_uid')
		            ->where('sessions.link', '=', $request->get('link'))
		            ->first();

		// $end = Carbon::now()->subMinutes(60); 
		// if($sess && $request->get('link') && $sess->session_start >= $end){
		// 	Session::put('link', $sess->link);
		// 	Session::put('user_uid', $sess->user_uid);
		// 	return $next($request);
		// }else{
		// 	DB::table('sessions')->where('link', '=', $request->get('link'))->delete();
		// 	Session::flush();
		// 	return Redirect('/');
		// }

		if($sess && $request->get('link')){
			Session::put('user_uid', $sess->user_uid);
			Session::put('user', $sess);
			Session::put('limit', 24);
			return $next($request);
		}else{
			//DB::table('sessions')->where('link', '=', $request->get('link'))->delete();
			Session::flush();
			return Redirect('/');
		}
	}

}
