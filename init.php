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

    header ('Access-Control-Allow-Origin: *');  
    
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