<?php

/**
 * 
 * @author Le Thi Xoan
 * @create at  Mar 29, 2016
 * Model for table pr
 */

class M_user_menu extends MY_Model
{

    public $table_name = 'user_menu';

    public $username = 'username';

    public $menu_id = 'menu_id';

    public $order_date = 'order_date';
    
    public function insert($arrData, $rank = false)
    {
        $id = $arrData['menu_id'];
        if (! empty($id)) {
            $this->table_name = 'menu';
            $this->id = 'id';
            $flag = $this->get_detail($id);
            $this->table_name = 'user_menu';
            if ($flag) {
                $check_exist = $this->get_list($arrData);
                if (!empty($check_exist))
                {
                    return false;
                }
                $q = $this->db->insert($this->table_name, $arrData);
                return $q;
            }
            return false;
        } else {
            return false;
        }
    }
    
}