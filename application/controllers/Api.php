<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function create() {
        $postBody = $this->input->raw_input_stream;
        $uuid = str_replace('-', '', $this->guuid4());
        mkdir('codes/' . $uuid, 0755, true);
        $file = 'codes/' . $uuid . '/' . $uuid . '.php';
        $data = $postBody;
        file_put_contents($file, $data);

        $returnArr = array(
            'platform' => 'php',
            'endpoint' => $uuid
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($returnArr, JSON_PRETTY_PRINT));
    }

    public function run() {
        $this->load->helper('url');
        $uuid = $this->uri->segment(3);
        $fileRead = exec('php codes/' . $uuid . '/' . $uuid . '.php');

        $returnArr = array(
            'result' => $fileRead
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($returnArr, JSON_PRETTY_PRINT));

    }

    public function example() {

        $returnArr = array(
            'exampleCode' => '<?php echo "TEST EXECUTE PHP";',
            'exampleRequest' => '{"exampleKey" : "exampleValue"}'
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($returnArr, JSON_PRETTY_PRINT));

    }

    public function guuid4() { // MC: Unique for more than 16 char
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

}
