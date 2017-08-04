<?php 
require_once('lib_task.php');
echo '<a href="/?sort=user' .getDescParam('user') . '">Sort By user</a> ';
echo '<a href="/?sort=email' .getDescParam('email') . '">Sort By email</a> ';
echo '<a href="/?sort=status' .getDescParam('status') . '">Sort By status</a> ';
if (isset($data['list'][0])) foreach($data['list'] as $row)
{
	showTask($row);
}
if ($data['pagination'])
{
	echo '<div class="pagination">';
	if ($data['pagination']['page']>1)
		echo '<a href="/' . getSortParams(true) . '">1</a>';
	$prev_page = $data['pagination']['page'] - 1;
	if ($prev_page > 1)
	{
		echo ' <a href="/?page=' . $prev_page . getSortParams() .  '">' . $prev_page . '</a>';
	}
	echo ' <b>' . $data['pagination']['page'] . '</b>';
	$next_page = $data['pagination']['page'] + 1;
	if ($next_page <= $data['pagination']['last'])
	{
		echo ' <a href="/?page=' . $next_page . getSortParams() . '">' . $next_page . '</a>';
	}
	if ($next_page < $data['pagination']['last'])
		echo ' <a href="/?page=' . $data['pagination']['last'] . getSortParams() . '">' . $data['pagination']['last'] . '</a>';
	echo '</div>';
}
if (admin == 0)
	echo '<a class="edit" href="/?module=tasks&act=edit&id=0">New</a>';
?>

<style>
.clear{
	clear: both;
}

.task a, .task b, .task span{
	display: block;
	float: left;
	margin: 5px;
	min-width: 10em;
}
.task a.email, .task span{
	min-width: 20em;
}

</style>