<?php
class Dan {
    public function add ($player, $author, $time, $text, $color, $type, $ip, $referer) {
        /** 插入 */
        $data = array (
            'id' => NULL,
            'player' => $player,
            'author' => $author,
            'time' => $time,
            'text' => $text,
            'color' => $color,
            'type' => $type,
            'ip' => $ip,
            'referer' => $referer
        );
        $ret = $GLOBALS['db']->insert ('dan', $data);
        
        /** 返回 */
        return $ret;
    }
    
    public function getinfo ($id = NULL, $player = NULL, $author = NULL, $time = NULL, $text = NULL, $color = NULL, $type = NULL, $ip = NULL, $referer = NULL, $limit = 0, $count = false) {
        /** 初始化变量 */
        $where = array ();
        $id === NULL ? : $where['AND']['id'] = $id;
        $player === NULL ? : $where['AND']['player'] = $player;
        $author === NULL ? : $where['AND']['author'] = $author;
        $time === NULL ? : $where['AND']['time'] = $time;
        $text === NULL ? : $where['AND']['text'] = $text;
        $color === NULL ? : $where['AND']['color'] = $color;
        $type === NULL ? : $where['AND']['type'] = $type;
        $ip === NULL ? : $where['AND']['ip'] = $ip;
        $referer === NULL ? : $where['AND']['referer'] = $referer;
        
        if ($limit != 0) {
            $where['LIMIT'] = $limit;
        }
        $ret = $count ? $GLOBALS['db']->count ('dan', $where) : $GLOBALS['db']->select ('dan', '*', $where);
        
        /** 返回 */
        return $ret;
    }
    
    public static function checkToken ($token) {
        /** 返回 */
        return true; 
    }
}