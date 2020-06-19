<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('assets/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
class Mypdf
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
	}

	public function GeneratePDF($view, $data = array(), $filename, $paper, $orientation)
	{
		$dompdf = new Dompdf();
		$html = $this->ci->load->view($view, $data, TRUE);
		$dompdf->set_option('enable_html5_parser', TRUE);
		$dompdf->loadHtml($html);
		$dompdf->setPaper($paper, $orientation);
		$dompdf->render();
		//ob_clean();
		$dompdf->stream($filename.".pdf", array("Attachment"=>FALSE));
	}

	public function GeneratePDFPesanan($view, $data = array(), $filename, $paper, $orientation)
	{
		$dompdf = new Dompdf();
		$html = $this->ci->load->view($view, $data, TRUE);
		$dompdf->set_option('enable_html5_parser', TRUE);
		$dompdf->set_option('isRemoteEnabled', TRUE);
		$dompdf->loadHtml($html);
		$dompdf->setPaper($paper, $orientation);
		$dompdf->render();
		//ob_clean();
		$dompdf->stream($filename.".pdf", array("Attachment"=>FALSE));
	}

}

/* End of file Mypdf.php */
/* Location: ./application/libraries/Mypdf.php */

?>