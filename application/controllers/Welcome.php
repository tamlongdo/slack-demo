<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * http://example.com/index.php/welcome
     * - or -
     * http://example.com/index.php/welcome/index
     * - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * 
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        var_dump('111111111');
        phpinfo();
    }

    public function demo()
    {
        $command = $_POST['command'];
        $text = $_POST['text'];
        $token = $_POST['token'];
        // Check the token and make sure the request is from our team
        if ($token != 'Moj5ICFUmSAXZOeE4eeHbw40') { // replace this with the token from your slash command configuration page
            $msg = "The token for the slash command doesn't match. Check your script.";
            die($msg);
            echo $msg;
        }
        echo $text;die;
        echo '======= MENU HÔM NAY===========<br>';
        for ($i = 0; $i < 5; $i++)
        {
            echo "$i. Cơm chang nước mắm. <br>";
        }
        echo '==================================';
        die;
    }
}
