<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('m_menu');
        $this->load->model('m_user');
        $this->load->model('m_user_menu');
    }
    public function index()
    {
        phpinfo();
    }

    public function demo()
    {
        //$command = $_REQUEST['command'];
        //$text = $_POST['text'];
        //$token = $_POST['token'];
        // Check the token and make sure the request is from our team
        /*
        if ($token != 'Moj5ICFUmSAXZOeE4eeHbw40') { // replace this with the token from your slash command configuration page
            $msg = "The token for the slash command doesn't match. Check your script.";
            die($msg);
            echo $msg;
        }
        echo $text;die;
        */
        $params = $this->input->post();
        $arrMenu = $this->m_menu->get_list();
        if (empty($arrMenu))
        {
            echo 'Hôm nay chưa có menu bạn ơi.';
        }
        else 
        {
            echo "======= MENU HÔM NAY===========\n";
            $i = 1;
            foreach ($arrMenu as $item)
            {
                echo "$i. $item->name. \n";
                $i++;
            }
            echo "==================================\n";
        }
        die;
    }
}
