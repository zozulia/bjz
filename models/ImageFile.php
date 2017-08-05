<?php
class ImageFile{

	private static $_sid = ''; //ID сессии. Поскольку работать более чем с одной картинкой одновременно пользователю запрещено, можно смело называть временные файлы несохраненных пока еще задач по идентификатору сессии
	private static $_instance = null;

	protected $_ext=''; //JPG/GIF/PNG
	protected $_available_ext = array('JPG','GIF','PNG');
	protected $_img_dir = __DIR__ .'/../images/';
	
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
			self::$_sid = session_id();
		}
		return self::$_instance;
	}

	public function getSid()
	{
		return self::$_sid;
	}

	public function getExt()
	{
		return $this->_ext;
	}

	public function setExt($ext)
	{
		$this->_ext = $ext;
	}

	function ResizeJpeg($imgname)
	{
        /* Пытаемся открыть */
        $source_image = @imagecreatefromjpeg($imgname);

        /* Если не удалось */
        if(!$source_image)
        {
            /* Создаем пустое изображение */
            $source_image  = imagecreatetruecolor(150, 30);
            $bgc = imagecolorallocate($im, 255, 255, 255);
            $tc  = imagecolorallocate($im, 0, 0, 0);

            imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

            /* Выводим сообщение об ошибке */
            imagestring($source_image, 1, 5, 5, 'Ошибка загрузки ' . $imgname, $tc);
        }

        //$img = imagescale($im, 150);
		$source_imagex = imagesx($source_image);
		$source_imagey = imagesy($source_image);
		$k = $source_imagex/$source_imagey;
		if ($k < 4/3)
		{
			$dest_imagey = 240;
			$dest_imagex = $dest_imagey * $k;
		}
		elseif($k > 4/3)
		{
			$dest_imagex = 320;
			$dest_imagey = $dest_imagex / $k;
		}
		else
		{
			$dest_imagey = 240;
			$dest_imagex = 320;
		}
		

		$dest_image = imagecreatetruecolor($dest_imagex, $dest_imagey);
		imagecopyresampled($dest_image, $source_image, 0, 0, 0, 0, 
					$dest_imagex, $dest_imagey, $source_imagex, $source_imagey);
        return $dest_image;
	}

	
	public function Upload()
	{
	
		if (isset($_FILES['userfile']['name']))
		{
			$filelocalname = $_FILES['userfile']['name'];
			if (strlen($filelocalname)>0)
			{
				$pieces = explode(".", $filelocalname);
				$fileext = strtoupper($pieces[count($pieces)-1]);
				if (!in_array($fileext, $this->_available_ext)) return 1;
				else
				{
					foreach ($this->_available_ext as $ext)
					{
						$dest = $this->_img_dir . self::$_sid . '.' . $ext;
						if (file_exists($dest)) unlink($dest);
					}
					$this->_ext = $fileext;
					if (isset($_SESSION['task'])) $_SESSION['task']['ext'] = $this->_ext;
					$dest = $this->_img_dir . self::$_sid . '.' . $this->_ext;
					if (move_uploaded_file($_FILES['userfile']['tmp_name'], $dest))
					{
						if (file_exists($dest))
						{
							$img = $this->ResizeJpeg($dest);
            
							imagejpeg($img, $dest);
							imagedestroy($img);
						}
						return 0;
					}
				}
			}
		}
	}

	public function Save($intTaskNum)
	{
		if (empty($this->_ext)) return 1;
		$source = $this->_img_dir . self::$_sid . '.' . $this->_ext;
		if (!file_exists($source)) return 2;
		foreach ($this->_available_ext as $ext){
			$dest = $this->_img_dir . $intTaskNum . '.' . $ext;
			if (file_exists($dest)) unlink($dest);
		}
		$dest = $this->_img_dir . $intTaskNum . '.' . $this->_ext;
		if (copy($source, $dest))
		{
			unlink($source);
			return 0;
		}
		else return 100;
	}
	
}
?>