<?php
require_once('lib_db.php');
class Tasks
{
	private static $_instance = null;
	protected $_data_dir = __DIR__ . '/../data/';
	protected $_arr_fields = array('id', 'user', 'email', 'ext', 'status', 'content');
	
	private function __construct() {
		// приватный конструктор ограничивает реализацию getInstance ()
		
	}
	
	protected function __clone() {
		// ограничивает клонирование объекта
	}
	
	static public function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function Load($id)
	{
		$sql = 'SELECT ' . implode(',',$this->_arr_fields) . ' FROM tasks WHERE id=' . (int)$id;
		$db = new DBMySQLPDO();
		$db->execute($sql);
		$ret = $db->_rows[0];
		$db->freeDB();
		return $ret;
	}
	
	public function TaskList($sort_field='user', $asc = true, $offset = 0, $size = 3)
	{
		if (!in_array($sort_field, $this->_arr_fields)) return 1;
		$sql = 'SELECT ' . implode(',',$this->_arr_fields) . ' FROM tasks ORDER BY ' . $sort_field . (!$asc?' DESC':'') . ' LIMIT ' . (int)$offset . ', ' . (int)$size;
		$db = new DBMySQLPDO();
		$db->execute($sql);
		$ret = array( 'list' => $db->_rows );
		$sql = 'SELECT COUNT(id) as cn FROM tasks;';
		$db->execute($sql);
		$ret['cn'] = $db->_rows[0]['cn'];
		$db->freeDB();
		return $ret;
	}

	public function Save()
	{
		$sql = 'INSERT INTO tasks( ' . implode(',',$this->_arr_fields) . ') VALUES(NULL, ?';
		$params = array( $_SESSION['task'][$this->_arr_fields[1]] );
		for($i = 2; $i < count($this->_arr_fields); $i++)
		{
			$sql .= ', ?';
			if(empty($_SESSION['task'][$this->_arr_fields[$i]]))
				$params[] = '';
			else
				$params[] = $_SESSION['task'][$this->_arr_fields[$i]];
		}
		$sql .= ');';
		
		$db = new DBMySQLPDO();
		$ret = $db->execute($sql, false, $params);
		foreach ($this->_arr_fields as $key=> $value)
		{
			unset($_SESSION['task'][$key]);
		}
		unset($_SESSION['task']);
		$db->freeDB();
		return $ret;
	}
	
	public function Update()
	{
		$sql = 'UPDATE tasks SET content=?, status=? WHERE id=? LIMIT 1;';
		$params = array( $_POST['content'], $_POST['status'], $_POST['id'] );
		
		$db = new DBMySQLPDO();
		$db->execute($sql, true, $params);
		foreach ($this->_arr_fields as $key=> $value)
		{
			unset($_SESSION['task'][$key]);
		}
		unset($_SESSION['task']);
		$db->freeDB();
		return $ret;
	}

}


?>