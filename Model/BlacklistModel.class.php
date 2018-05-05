<?php
    class BlacklistModel extends FLModel {
        public function add () {
            
        }
        
        public function getinfo ($id = NULL, $value = NULL, $type = NULL, $limit = 0, $count = false) {
    		/** 初始化变量 */
    		$where = array ();
    		$id === NULL ? : $where['AND']['id'] = $id;
    		$value === NULL ? : $where['AND']['value'] = $value;
    		$type === NULL ? : $where['AND']['type'] = $type;
    		
    		if ($limit != 0) {
    			$where['LIMIT'] = $limit;
    		}
    		$ret = $count ? $this->db->count ('blacklist', $where) : $this->db->select ('blacklist', '*', $where);
    
    		/** 返回 */
    		return $ret;
    	}
    }