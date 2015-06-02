<?php

//saving pdf
require_once(dirname(__FILE__).'/lib/tcpdf/config/tcpdf_config.php');
require_once(dirname(__FILE__).'/lib/tcpdf/tcpdf.php');

$svg = $_POST['output_svg'];
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

if (@file_exists(dirname(__FILE__).'/lib/tcpdf/examples/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lib/tcpdf/examples/lang/eng.php');
	$pdf->setLanguageArray($l);
}
$pdf->AddPage();
$pdf->ImageSVG("@$svg", $x=15, $y=30, $w='', $h='', $border=1, $fitonpage=false);

$file_pdf = "outputs/" . $_POST['filename'] . ".pdf";
$fp = fopen($file_pdf, "w"); 
fwrite($fp,  ($pdf->Output('wtf.pdf', 'S')) ); // параметр F - надо доступ
fclose($fp);
exec('convert -density 343 outputs/template.pdf outputs/template.pdf');
//saving svg
$file_svg = "outputs/" . $_POST['filename'] . ".svg";
//TODO: проверка совпадения имен
$fp = fopen($file_svg, "w"); 
fwrite($fp, $svg);
fclose($fp);

?>