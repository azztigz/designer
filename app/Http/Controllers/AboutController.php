<?php namespace App\Http\Controllers;

class AboutController extends Controller {

	public function __construct()
	{
		$this->middleware('guest');
	}

	public function index()
	{
		$data['test'] = 'test';
		return view('dp.about', $data);
	}

}
