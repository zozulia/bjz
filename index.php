<?php
	session_start();
	if (!empty($_GET['login']))
	{
		if (($_GET['login']=='admin')&&($_GET['password']=='123'))
		{
			$_SESSION['admin'] = 1;
		}
		else
		{
			$_SESSION['admin'] = 0;
		}
	}
	elseif($_GET['act']=='logout')
		$_SESSION['admin'] = 0;
	
	if (isset($_SESSION['admin']) && ($_SESSION['admin'] ==1)) define('admin', 1);
	else define('admin', 0);
	
	define('page_size', '3');
	require_once('views/head.php');
	if (isset($_REQUEST['module']))
	{
		$module_name = 'controllers/' . substr($_REQUEST['module'], 0, 15) . '.php';
		require_once($module_name);
	}
	else
	{
		require_once('models/Tasks.php');
		$model = Tasks::getInstance();
		if (isset($_GET['page'])){
			$offset = page_size * ($_GET['page'] - 1);
			
		}
		else
			$offset = 0;
		if (isset($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else
			$sort = 'id';
		$bAsc = empty($_GET['asc'])?true:false;
		$data = array();
		$data = $model->TaskList($sort, $bAsc, $offset, page_size);
		if ($data['cn']>page_size)
		{
			$data['pagination']['last'] = (int)($data['cn']/page_size) + (($data['cn']%page_size > 0)?1:0);
			$data['pagination']['page'] = ((int)$_GET['page']>0)?(int)$_GET['page']:1;
		}
		require_once('views/list.php');
	}
	//require_once('views/head.php');
?>
</body>
</html>