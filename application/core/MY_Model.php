<?php

/**
 *
 * @author : Long Do
 * @created at : 2016/03/25
 * MY_Model: base model, others model should be extends from this
 *
 */
class MY_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * Get list of this table
     *
     * @author Long Do
     * @return object | null $ret
     *        
     */
    public function get_list($rank = false, $order_by = NULL, $order_way = NULL, $row_limit = NULL, $where = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if ($rank) {
            $this->db->order_by($this->rank, "asc");
        } elseif (! is_null($order_by) && ! is_null($order_way)) {
            $this->db->order_by($order_by, $order_way);
        }
        if ($row_limit){
            $this->db->limit($row_limit);
        }
        if($where){
            $this->db->where($where);
        }
        $q = $this->db->get()->result();
        $ret = ! empty($q) ? $q : null;
        return $ret;
    }

    /**
     *
     * Get 1 row of this table
     *
     * @author Long Do
     * @param integer $id            
     * @return object | null $ret
     *        
     */
    public function get_detail($id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where($this->id, $id);
        $q = $this->db->get()->result();
        $ret = ! empty($q) ? $q[0] : null;
        return $ret;
    }

    /**
     * Insert row to db
     *
     * @author Long Do
     * @param array $arrData            
     * @return bool $q
     */
    public function insert($arrData, $rank = false)
    {
        //Check xss
        $arrData = $this->security->xss_clean($arrData);
        $list_field = $this->db->list_fields($this->table_name);
        if (in_array('created_at', $list_field)) {
            $arrData['created_at'] = date('Y-m-d H:i:s');
        }
        if ($rank) {
            $arrData['rank'] = $this->get_max_rank() + 1;
        }
        $q = $this->db->insert($this->table_name, $arrData);
        return $q;
    }

    /**
     * Update row to db
     *
     * @author Long Do
     * @param array $arrData            
     * @param integer $id            
     * @return bool $q
     */
    public function update($arrData, $id = "")
    {
        if (! empty($id)) {
            $flag = $this->get_detail($id);
            if ($flag) {
                $this->db->where($this->id, $id);
                $list_field = $this->db->list_fields($this->table_name);
                if (in_array('updated_at', $list_field)) {
                    $arrData['updated_at'] = date('Y-m-d H:i:s');
                }
                unset($arrData[$this->id]);
                $arrData = $this->security->xss_clean($arrData);
                $q = $this->db->update($this->table_name, $arrData);
                return $q;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * DELTE row
     *
     * @author Long Do
     * @param integer $id            
     * @return bool $q
     */
    public function delete($id = "", $rank = false)
    {
        if (! empty($id)) {
            $flag = $this->get_detail($id);
            if ($flag) {
                
                if ($rank) {
                    $arrData = $this->get_list(true);
                    foreach ($arrData as $item) {
                        if ($item->id == $id) {
                            $cur_rank = $item->rank;
                        }
                    }
                    $arrNewRank = array();
                    foreach ($arrData as $item) {
                        if ($item->rank > $cur_rank) {
                            $arrNewRank[$item->id] = $item->rank - 1;
                        }
                    }
                    if (! empty($arrNewRank)) {
                        $this->update_rank($arrNewRank);
                    }
                }
                $this->db->where($this->id, $id);
                $q = $this->db->delete($this->table_name);
                return $q;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * Update rank
     *
     * @param array $arrData
     *            return bool $q
     */
    public function update_rank($arrData)
    {
        foreach ($arrData as $id => $rank) {
            $this->db->where($this->id, $id);
            $this->db->update($this->table_name, array(
                $this->rank => $rank
            ));
        }
    }

    /**
     */
    public function get_max_rank()
    {
        $this->db->select_max($this->rank);
        $q = $this->db->get($this->table_name);
        return $q->row()->{$this->rank};
    }

    /**
     */
    public function get_max($field)
    {
        $this->db->select_max($field);
        $q = $this->db->get($this->table_name);
        return $q->row()->{$field};
    }
    
    /**
     * 
     */
    public function should_add_slug_rule($id, $slug_name, $slug )
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where($this->id, $id);
        $this->db->where($slug_name, $slug);
        $q = $this->db->get()->result();
        $ret = ! empty($q) ? false : true;
        return $ret;
    }
}