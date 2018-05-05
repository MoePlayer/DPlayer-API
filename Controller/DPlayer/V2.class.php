<?php
    class V2 extends FLController {
        public function run () {
            $danModel = new DanModel;
            
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
                $systemModel = new SystemModel;
                $blacklistModel = new BlacklistModel;
                $IP = $systemModel->GetIP ();
                
                $data = json_decode(file_get_contents ('php://input'), true);
                
                if ($blacklistModel->getinfo(NULL, $IP, 1)) {
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
                
                if ($blacklistModel->getinfo(NULL, $data['author'], 0)) {
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
        }
        
        public function Video () {
            $systemModel = new SystemModel;
            $blacklistModel = new BlacklistModel;
            $IP = $systemModel->GetIP ();
            
            if ($blacklistModel->getinfo(NULL, $IP, 1)) {
                exit (json_encode (array ('code' => -1, 'msg' => 'Rejected for black ip.')));
            }
            
            if ($_GET['type'] == 'BiliBili') {
                if ($_GET['cid']) {
                    header ('Location:' . $systemModel->GetVideoURL($_GET['cid']));
                    exit();
                } else if ($_GET['aid']) {
                    $aid2cid = new Aid2CidModel;
                    
                    if (!$aid2cid->getinfo($_GET['aid'])[0]) {
                        $data = json_decode($systemModel->fetch('https://www.bilibili.com/widget/getPageList?aid=' . $_GET['aid']), true);
                        $aid2cid->add ($_GET['aid'], $data[0]['cid'], $IP);
                    }

                    header ('Location:' . $systemModel->GetVideoURL($aid2cid->getinfo($_GET['aid'])[0]['cid']));
                    exit();
                } else {
                    exit (json_encode (array ('code' => -3, 'msg' => 'Rejected for illegal data.')));
                }
            }
        }
        
        public function Dan () {
            $systemModel = new SystemModel;
            $blacklistModel = new BlacklistModel;
            $IP = $systemModel->GetIP ();
            
            if ($blacklistModel->getinfo(NULL, $IP, 1)) {
                exit (json_encode (array ('code' => -1, 'msg' => 'Rejected for black ip.')));
            }
            
            if ($_GET['type'] == 'BiliBili') {
                if ($_GET['cid']) {
                    exit (json_encode (array ('code' => 1, 'danmaku' => $systemModel->GetVideoDan ($_GET['cid']))));
                } else if ($_GET['aid']) {
                    $aid2cid = new Aid2CidModel;
                    
                    if (!$aid2cid->getinfo($_GET['aid'])[0]) {
                        $data = json_decode($systemModel->fetch('https://www.bilibili.com/widget/getPageList?aid=' . $_GET['aid']), true);
                        $aid2cid->add ($_GET['aid'], $data[0]['cid'], $IP);
                    }
                    
                    exit (json_encode (array ('code' => 1, 'danmaku' => $systemModel->GetVideoDan($aid2cid->getinfo ($_GET['aid'])[0]['cid']))));
                } else {
                    exit (json_encode (array ('code' => -3, 'msg' => 'Rejected for illegal data.')));
                }
            }
        }
    }