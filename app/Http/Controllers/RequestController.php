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

class RequestController extends Controller {

	public function index(){
	    return view('dp.request');
	}

	public function goEditor(){
		return view('dp.request');
	}

}