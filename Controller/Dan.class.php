<?php
    class Dan extends FLController {
        public function run () {
            $systemModel = new SystemModel;
            $blanklistModel = new BlanklistModel;
            $IP = $systemModel->GetIP ();
            
            if ($blanklistModel->getinfo(NULL, $IP, 1)) {
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