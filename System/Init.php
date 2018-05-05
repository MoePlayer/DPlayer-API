<?php
    /** 定义 */
    define ('FRAME_PATH', APP_PATH . '/System');
    define ('CONFIG_PATH', APP_PATH . '/Config');
    define ('MODEL_PATH', APP_PATH . '/Model');
    define ('VIEW_PATH', APP_PATH . '/Templates');
    define ('CONTROLLER_PATH', APP_PATH . '/Controller');
    define ('LIB_PATH', APP_PATH . '/Lib');
    define ('LOG_PATH', APP_PATH . '/Log');

    define ('FRAME_URL', APP_URL . '/System');
    define ('CONFIG_URL', APP_URL . '/Config');
    define ('MODEL_URL', APP_URL . '/Model');
    define ('VIEW_URL', APP_URL . '/Templates');
    define ('CONTROLLER_URL', APP_URL . '/Controller');
    define ('LIB_URL', APP_URL . '/Lib');
    define ('LOG_URL', APP_URL . '/Log');

    define ('FRAME_VERIONS', '1.9.5');

    /** 引入 */
    require_once FRAME_PATH . '/Core.php';
    foreach (glob (CONFIG_PATH . '/*.php') as $configFile) {
        require_once $configFile;
    }
    
    header ('Access-Control-Allow-Origin: *');  
    header ('Access-Control-Allow-Headers: Content-Type, Content-Length, Authorization, Accept, X-Requested-With , yourHeaderFeild'); 
    header ('Access-Control-Allow-Methods: PUT, POST, GET, DELETE, OPTIONS'); 
    header ('Cache-control: no-cache'); 
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        exit();
    } 
    
    /** 运行框架 */
    $f = new Core;
    $f->run (3);
