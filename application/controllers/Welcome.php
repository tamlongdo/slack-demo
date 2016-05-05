<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('m_menu');
        $this->load->model('m_user');
        $this->load->model('m_user_menu');
        /*
        $token = $params['token'];
        if ($token != 'Moj5ICFUmSAXZOeE4eeHbw40') { // replace this with the token from your slash command configuration page
            $msg = "The token for the slash command doesn't match. Check your script.";
            die($msg);
            echo $msg;
        }
        echo $text;die;
        echo '111111';
        */
    }
    public function index()
    {
        phpinfo();
    }

    public function menu()
    {
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
                echo "#$i. $item->name. \n";
                $i++;
            }
            echo "==================================\n";
            echo "/order #số\n";
            
        }
        die;
    }
    
    public function order()
    {
        $params = $_REQUEST;
        $menu_id = $params['text'];
        $username = $params['user_name'];
        $order_date = date("Y-m-d 00:00:00");
        $arrData = array(
            'menu_id'   =>  $menu_id,
            'username'  =>  $username,
            'order_date'=>  $order_date
        );
        if (!$this->m_user_menu->insert($arrData))
        {
            echo 'Đặt món không thành công.';
        }
        else 
        {
            $menu_name = $this->m_menu->get_detail($menu_id)->name;
            echo "$username đã đặt thành công món #$menu_id $menu_name\n";
            echo "Kiểm tra lại chi tiết bằng /check-order\n";
        }
        die;
    }
    
    public function check_order()
    {
        $params = $_REQUEST;
        $username = $params['user_name'];
        $ret = $this->m_user_menu->get_detail_user_menu($username);
        if (!$ret) 
        {
            echo "Hôm nay bạn chưa đặt món.\n";
            echo "/menu để xem menu ngày hôm nay.";
        }
        else
        {
            echo "Hôm nay bạn đã đặt món #$ret->menu_id $ret->name.\n";
        }
        die;
    }
    
    
}
