<?php
    // 检查是否已安装
    if (!is_file ('../config.php')) {
        header('HTTP/1.1 503 Service Unavailable');
        exit ();
    }
    
    // 加载配置
    require_once '../init.php';
	
    // 执行各类操作
    $danModel = new Dan;
    
    if ($_GET['id']) {
        if (!$_GET['max']) {
            $_GET['max'] = 0;
        }
        $typeMap = [
            'right' => 0,
            'top' => 1,
            'bottom' => 2
        ];
        
        $ret = [];
        foreach ($danModel->getinfo(NULL, $_GET['id'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, $_GET['max']) as $data) {
            $ret[] = [
                $data['time'],
                $typeMap[$data['type']],
                $data['color'],
                $data['author'],
                $data['text']
            ];   
        }
        
        exit (json_encode (array ('code' => 0, 'version' => 2, 'danmaku' => $ret)));
    } else {
        $blanklistModel = new Blacklist;
        $IP = System::GetIP();
        
        $data = json_decode(file_get_contents ('php://input'), true);
        
        if ($blanklistModel->getinfo(NULL, $IP, 1)) {
            exit (json_encode (array ('code' => 1, 'msg' => 'Rejected for black ip.')));
        }
        
        if (isset($_SESSION["time"])) { 
            if (time() - $_SESSION["time"] < 1) { 
                exit (json_encode (array ('code' => 2, 'msg' => 'Rejected for frequent operation.')));
            } else { 
                $_SESSION["time"] = time(); 
            } 
        } else { 
            $_SESSION["time"] = time(); 
        } 
        
        if (!isset ($data['player']) || !isset ($data['author']) || !isset ($data['time']) || !isset ($data['text']) || !isset ($data['color']) || !isset ($data['type'])|| mb_strlen ($data['text'], 'UTF-8') >= 30) {
            exit (json_encode (array ('code' => 3, 'msg' => 'Rejected for illegal data.')));
        }
        
        if (!$danModel->checkToken($data['token'])) {
            exit (json_encode (array ('code' => 4, 'msg' => 'Rejected for illegal token: ' . $data['token'])));
        }
        
        if ($blanklistModel->getinfo(NULL, $data['author'], 0)) {
            exit (json_encode (array ('code' => 5, 'msg' => 'Rejected for black user.')));
        }
        
        $add = $danModel->add ($data['player'], $data['author'], $data['time'], $data['text'], $data['color'], $data['type'], $IP, $_SERVER['HTTP_REFERER']);
        
        if ($add) {
            $data = $danModel->getinfo($add)[0];
            $ret = [
                '_id' => $data['id'],
                '__v' => 0,
                'player' => [$data['player']],
                'author' => $data['author'],
                'time' => $data['time'],
                'text' => $data['text'],
                'color' => $data['color'],
                'type' => $data['type'],
                'ip' => $data['ip'],
                'referer' => $data['referer']  
            ];
            
            exit (json_encode (array ('code' => 0, 'data' => $ret)));
        } else {
            exit (json_encode (array ('code' => -1, 'msg' => 'Error happens, please contact system administrator.')));
        }
    }
?>