<?php
	//init.php 文件中个，我们完成初始化工作
	header("content-type:text/html;charset=utf-8");
	//确定路径
	define('ROOT_PATH', dirname(__DIR__) . '/');
	//定义模板文件夹的路径
	define('TMP_PATH', ROOT_PATH . 'template/');
	//定义lib文件夹的路径
	define('LIB_PATH', ROOT_PATH . 'lib/');
	require_once  LIB_PATH . 'DAOMySQLi.class.php';
	//调用我们的MySQLi.class.php 文件
	$db_config = array(
		'host' => 'localhost',
		'user' => 'root',
		'pw' => 'root',
		'dbname' => 'test',
		'port' => '3306',
		'charset' => 'utf8'
	);
	$dao = DAOMySQLi::getSingleton($db_config);
