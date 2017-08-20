<?php
	class Aid2CidModel extends FLModel {
        public function add ($aid, $cid, $ip) {
            /** 插入 */
    		$data = array (
    			'aid' => $aid,
    			'cid' => $cid,
    			'ip' => $ip
    		);
    		$ret = $this->db->insert ('aid2cid', $data);
    
    		/** 返回 */
    		return $ret;
        }
        
        public function getinfo ($aid = NULL, $cid = NULL, $ip = NULL, $limit = 0, $count = false) {
    		/** 初始化变量 */
    		$where = array ();
    		$aid === NULL ? : $where['AND']['aid'] = $aid;
    		$cid === NULL ? : $where['AND']['cid'] = $cid;
    		$ip === NULL ? : $where['AND']['ip'] = $ip;
    		
    		if ($limit != 0) {
    			$where['LIMIT'] = $limit;
    		}
    		$ret = $count ? $this->db->count ('aid2cid', $where) : $this->db->select ('aid2cid', '*', $where);
    
    		/** 返回 */
    		return $ret;
    	}
    }