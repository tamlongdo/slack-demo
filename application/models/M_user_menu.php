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

    public $user_id = 'user_id';

    public $menu_id = 'menu_id';

    public $order_date = 'order_date';
    
    public function insert($arrData, $rank = false)
    {
        $id = $arrData['menu_id'];
        if (! empty($id)) {
            $this->table_name = 'menu';
            $flag = $this->get_detail($id);
            $this->table_name = 'user_menu';
            if ($flag) {
                $q = $this->db->insert($this->table_name, $arrData);
                return $q;
            }
            return false;
        } else {
            return false;
        }
    }
}