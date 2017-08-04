<?php
switch($_REQUEST['act'])
{
	case 'edit':
			require_once(__DIR__ . '/../models/Tasks.php');
			$model = Tasks::getInstance();
			require_once(__DIR__ . '/../views/lib_task.php');
			if (( admin == 0 ) && (!empty($_SESSION['task'])))
			{
				$_SESSION['task']['id'] = 0;
				$arrData = $_SESSION['task'];
			}
			else
			{
				$arrData = $model->Load($_REQUEST['id']);
			}
			formTask($arrData);
		break;
	case 'save':
			if (!isset($_SESSION['task'])) $_SESSION['task'] = array();
			foreach( $_POST as $key => $value )
				$_SESSION['task'][$key] = $value;
			require_once( __DIR__ . '/../models/Tasks.php' );
			require_once( __DIR__ . '/../models/ImageFile.php' );
			$model = Tasks::getInstance();
			$model_img = ImageFile::getInstance();
			$model_img->setExt($_SESSION['task']['ext']);
			if ( admin > 0 )
			{
				$model->Update();
			}
			elseif (( admin == 0 ) && ($_SESSION['task']['id']==0))
			{
				$model_img->Upload();
				$_SESSION['task']['ext'] = $model_img->getExt();
				if(empty($_REQUEST['force']))
				{
					require_once(__DIR__ . '/../views/lib_task.php');
					showTask($_SESSION['task']);
					echo '<a href="/?module=tasks&act=edit&id=' . $_POST['id'] . '">Edit</a>';
					echo ' <a href="/?module=tasks&act=save&id=' . $_POST['id'] . '&force=1">Save</a>';
				}
				else
				{
					$imgId = $model->Save();
					if(($model_img->getExt() == '') || ($model_img->Save($imgId) == 0)) echo 'Task saved';
				}
			}
		break;
	default:
			require_once(__DIR__ . '/../models/Tasks.php');
			$model = Tasks::getInstance();
			require_once(__DIR__ . '/../views/lib_task.php');
			showTask($model->Load($_REQUEST['id']));
		break;
}
?>