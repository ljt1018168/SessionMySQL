<?php


/**
 * session存储MySQL的工具类
 */
class SessionMySQL 
{

	private $dao;// 操作MySQL数据库服务器对象

	public function __construct()
	{
		// 设置session的处理器
		ini_set('session.save_handler', 'user');
		session_set_save_handler(
			array($this, 'sessionBegin'),
			array($this, 'sessionEnd'),
			array($this, 'sessionRead'),
			array($this, 'sessionWrite'),
			array($this, 'sessionDelete'),
			array($this, 'sessionGC')
			);

		// 开启session
		session_start();
	}

	public function sessionRead($session_id)
	{
		$sql = "SELECT `session_data` FROM `tn_session` WHERE `session_id`='$session_id'";
		$row = $this->dao->fetchRow($sql);
		return $row ? $row['session_data'] : '';

	}
	public function sessionWrite($session_id, $session_data)
	{
		$sql = "REPLACE INTO `tn_session` VALUES ('$session_id', '$session_data', unix_timestamp())";
		return $this->dao->query($sql);

	}
	public function sessionDelete($session_id)
	{
		$sql = "DELETE FROM `tn_session` WHERE `session_id`='$session_id'";
		return $this->dao->query($sql);

	}
	public function sessionGC($maxlifetime)
	{
		$sql = "DELETE FROM `tn_session` WHERE `last_write` < unix_timestamp()-$maxlifetime";
		return $this->dao->query($sql);

	}
	public function sessionBegin()
	{
		// 数据库操作对象的初始化
		// 妥协的保证不重复加载的做法，实操中使用自动加载
		require_once LIB_PATH . 'DAOMySQLi.class.php';
		$db_config = array(
			'host' => 'localhost',
			'user' => 'root',
			'pw' => 'hellokang',
			'dbname' => 'blog1',
			'port' => '3306',
			'charset' => 'utf8'
		);
		$this->dao = DAOMySQLi::getSingleton($db_config);

	}
	public function sessionEnd()
	{
		return true;
	}
}


/*
// session数据表
drop table if exists tn_session;
create table tn_session (
	session_id varchar(40) not null, -- 相当于文件名
	session_data text, -- 相当于文件内容，存储序列化好的session数据
	last_write int not null default 0, -- 最后的修改时间，unix时间戳的形式进行表示
	primary key (session_id)
) charset=utf8;
*/