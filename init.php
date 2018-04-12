<?php
	// 定义
	define ('SYSTEM_ROOT', __DIR__);
	date_default_timezone_set ('Asia/Shanghai');
	
	// 引入
	require_once 'config.php';
	require_once 'lib/DB.php';
	require_once 'lib/Dan.php';
	require_once 'lib/Blacklist.php';
	require_once 'lib/System.php';

	// 连接数据库
	$db = new medoo (array (
		'database_type' => 'mysql',
		'database_name' => DBNAME,
		'server' => DBHOST,
		'username' => DBUSER,
		'password' => DBPASS,
		'charset' => 'utf8',
		'option' => array (
			PDO::ATTR_PERSISTENT => DBPERSISTENT
		)
	));
	
    session_start();
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit('ok');
    }