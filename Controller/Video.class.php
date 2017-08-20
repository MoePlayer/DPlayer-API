<?php
    class Video extends FLController {
        public function run () {
            $systemModel = new SystemModel;
            $blanklistModel = new BlanklistModel;
            $IP = $systemModel->GetIP ();
            
            if ($blanklistModel->getinfo(NULL, $IP, 1)) {
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
    }