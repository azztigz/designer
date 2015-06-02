<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;

class TempController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Main Page Controller
	|--------------------------------------------------------------------------
	|
	|
	*/
	public function __construct()
	{
		if (!file_exists(config()->get('pdf.storage_path')))
			mkdir(config()->get('pdf.storage_path'), 0777);
		if (!file_exists(config()->get('pdf.storage_path') .'/svg'))
			mkdir(config()->get('pdf.storage_path') .'/svg', 0777);
	}

	public function index(Request $request)
	{
		return view('temp.editor');
	}

	public function pickTemplate()
	{
		return view('temp.pick-template');
	}

	public function fileSave(Request $request)
	{
		$svg = $request->input('output_svg');
		$filename = $request->input('filename');

		if (!empty($svg) AND !empty($filename))
		{
			require_once public_path('../vendor/tecnick.com/tcpdf/config/tcpdf_config.php');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			require_once public_path('../vendor/tecnick.com/tcpdf/examples/lang/eng.php');
			$pdf->setLanguageArray($l);

			$pdf->AddPage();
			$pdf->ImageSVG("@$svg", $x=15, $y=30, $w='', $h='', $border=1, $fitonpage=false);
			$pdfFile = config()->get('pdf.storage_path') ."/". $filename . ".pdf";
			$fp = fopen($pdfFile, "w"); 
			fwrite($fp,  ($pdf->Output('wtf.pdf', 'S')) ); // параметр F - надо доступ
			fclose($fp);
			exec('convert -density 343 outputs/template.pdf outputs/template.pdf');

			$svgFile = config()->get('pdf.storage_path') ."/svg/". $filename .".svg";
			$fp = fopen($svgFile, "w"); 
			fwrite($fp, $svg);
			fclose($fp);
		}
		return response()->json([]);
	}

}
