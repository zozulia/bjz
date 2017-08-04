<?php
function showTask($row)
{
	echo '<div class="task">';
	if ($row['ext'] != '')
		echo '<a href="/?module=tasks&act=view&id=' . $row['id'] . '"><img src="/images/' . (($row['id'] > 0)?$row['id']:session_id()) . '.' . $row['ext'] . '" align="left"></a>';
	echo '<a href="/?module=tasks&act=view&id=' . $row['id'] . '">' . $row['user'] . '</a>';
	echo '<a class="email" href="/?module=tasks&act=view&id=' . $row['id'] . '">' . $row['email'] . '</a>';
	echo '<b>' . (($row['status']>0)?'done':'uncomplete') . '</b>';
	echo '<span>' . $row['content'] . '</span>';
	if (admin > 0)
		echo '<a class="edit" href="/?module=tasks&act=edit&id=' . $row['id'] . '">Edit</a>';
	echo '</div>';
	echo '<div class="clear"></div>';
}
function formTask($row)
{
	$id = (int)$row['id'];
	echo '<form enctype="multipart/form-data" action="/" method="POST">';
	echo '<div class="task">';
	echo '<input type="hidden" name="module" value="tasks">';
	echo '<input type="hidden" name="act" value="save">';
	echo '<input type="hidden" name="id" value="' . $id . '">';
	if ($row['ext'] != '')
		echo '<img src="/images/' . (($row['id'] > 0)?$row['id']:session_id()) . '.' . $row['ext'] . '" align="left">';
	if($row['id']==0){
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="8000000" />';
		echo '<input type="file" name="userfile" /><br />';
		echo '<input type="text" name="user" value="' . $row['user'] . '" placeholder="user"><br />';
		echo '<input type="email" name="email" value="' . $row['email'] . '" placeholder="email"><br />';
	}
	else{
		echo '<span class="user">' . $row['user'] . '</span>';
		echo '<span class="email">' . $row['email'] . '</span>';
	}
	echo '<label for="statusinp">Status (done/uncomplete)</label><input type="checkbox" name="status" value="1" id="statusinp"' . (($row['status']>0)?' checked':'') . '><br />';
	echo '<textarea name="content" placeholder="content">' . $row['content'] . '</textarea>';
	echo '</div>';
	echo '<input type="submit" value="Submit">';
	echo '</form>';
?>
<script>
	function pred()
	{
		$('#pred.task .user').html($('input[name="user"]').val());
		$('#pred.task .email').html($('input[name="email"]').val());
		if ($('input[name="status"]').attr('checked')) $('#pred.task>.status').html("done");
		else $('#pred.task .status').html("uncomlplete");
		$('#pred.task .content').html($('textarea[name="content"]').val());
	}
</script>
<button class="pred" onclick="pred()">Предварительный просмотр</button>
<div class="task" id="pred">
	<a class="user"></a>
	<a class="email"></a>
	<b class="status"></b>
	<span class="content"></span>
</div>
<?php	
}

function getSortParams($bFirstparam=false)
{
	$ret = '';
	$delimeter = '';
	if (isset($_GET['sort'])){
		$ret .= $delimeter . 'sort=' . $_GET['sort'];
		$delimeter = '&';
	}
	if (isset($_GET['asc'])){
		$ret .= $delimeter . 'asc=' . $_GET['asc'];
		$delimeter = '&';
	}
	if (isset($ret{1}))
	{
		return ($bFirstparam?'?':'&') . $ret;
	}
	else return '';
}

function getDescParam($sortparam)
{
	if($sortparam==$_GET['sort'])
	{
		if (empty($_GET['asc'])) return '&asc=desc';
		else return '';
	}
	else
	{
		return '';
	}
}
?>