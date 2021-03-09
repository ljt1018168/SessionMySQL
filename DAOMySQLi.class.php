<?php
	
	//开发一个DAOMySQLi.class.php 数据库操作类

	
	class DAOMySQLi{
		
		//定义属性
		//主机名
		private $_host;
		private $_user;
		private $_pw;
		private $_dbname;
		private $_port;
		private $_charset;

		private static $_instance;
		//$_mySQLi 表示一个MySQLi的对象
		private $_mySQLi;

		//单例
		private function __construct(array $option = array()){
			
			$this->_initOption($option);
			$this->_initMySQLi();
		}

		//对_mySQLi 对象的初识化
		private function _initMySQLi(){
			
			//初识化$_mySQLi对象
			$this->_mySQLi = new MySQLi($this->_host, $this->_user, $this->_pw, $this->_dbname, $this->_port);
			if($this->_mySQLi->connect_errno){
				die('连接失败' . $this->_mySQLi->connect_error);
			}

			//设置字符集
			$this->_mySQLi->set_charset($this->_charset);

		}

		//把对成员属性的初识化专门的写到一个函数中
		private function _initOption(array $option){
			//就可以初识化$_mySQLi
			//我们需要对传入的$option 的数据进行验证.
			$this->_host = isset($option['host']) ? $option['host'] : '';
			$this->_user = isset($option['user']) ? $option['user'] : '';
			$this->_pw = isset($option['pw']) ? $option['pw'] : '';
			$this->_dbname = isset($option['dbname']) ? $option['dbname'] : '';
			$this->_port = isset($option['port']) ? $option['port'] : '';
			$this->_charset = isset($option['charset']) ? $option['charset'] : '';
			
			if($this->_host == '' || $this->_user == '' || $this->_pw == '' || $this->_dbname == '' || $this->_port == '' || $this->_charset == ''){
				echo '你的数据库配置信息有问题';
				exit;
			}
		}

		//防止克隆
		private function __clone(){
		}

		public static function getSingleton(array $option = array()){
			
			//如果发现没有创建过
			if(!(self::$_instance instanceof self)){
				self::$_instance = new self($option);
			}
			return self::$_instance;
		}

		//开发一个函数，可以对mysql数据库进行dml操作
		public function query($sql = ''){
			
			if(!($res = $this->_mySQLi->query($sql))){
				echo '操作失败';
				echo '错误的sql语句是' . $sql . '错误的信息是' . $this->_mySQLi->error;
				exit;	
			}
			return $res;
		}

		//开发函数，可以执行查询select的操作，并且返回这个结果-array
		public function fetchAll($sql = ''){
			
			$res = $this->query($sql);

			//对$res 进行封装到一个数组.
			$arr = array();
			while($row = $res->fetch_assoc()){
				$arr[] = $row;
			}
			//马上释放结果集
			$res->free();
			return $arr;

		}

		//当我们需要其它函数的时候，可以灵活的添加。
		public function fetchRow($sql) {
			// 执行SQL
			$result = $this->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->free();
			return $row ? $row : false;
		}
	}