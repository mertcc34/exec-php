<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function createurl(){
        $postBody = $this->input->raw_input_stream;
        $uuid = str_replace('-', '', $this->guuid4());
        $file = 'codes/'.$uuid.'.php';
        $data = $postBody;
        file_put_contents($file, $data);
        $fileRead = exec('php codes/'.$uuid.'.php');
        $returnArr = array('url' => 'localhost:8000/'.$uuid);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($returnArr, JSON_PRETTY_PRINT));
	}

    public function execute(){
        $this->load->helper('url');
        $uuid = $this->uri->segment(3);
        $fileRead = exec('php codes/'.$uuid.'.php');
        $returnArr = array('result' => $fileRead);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($returnArr, JSON_PRETTY_PRINT));

    }

    public function guuid4() {
	    $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

}
