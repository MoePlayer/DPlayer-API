<?php
    // 检查是否已安装
    if (!is_file ('../config.php')) {
        header('HTTP/1.1 503 Service Unavailable');
        exit ();
    }
    
    // 加载配置
    require_once '../init.php';
	
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	    exit('ok');   
	}
	
    // 执行各类操作
    session_start();
    $danModel = new Dan;
?>