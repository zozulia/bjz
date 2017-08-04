<!DOCTYPE>
<html>
<head>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js?ver=3.9.2'></script>
<style>
.clear{
	clear: both;
}

.task{
	border-top: solid 1px gray;
}
.task a, .task b, .task span{
	display: block;
	float: left;
	margin: 5px;
	min-width: 10em;
}
.task .email, .task span{
	min-width: 20em;
}

</style>
</HEAD>
<body>
<a href="/">List of Tasks</a>
<?php
if (admin==0) echo '<form class="login" action="/?act=login"><input type="text" name="login" placeholder="admin login"><input type="password" name="password" placeholder="admin password"><input type="submit" value="Sign In"></form><br />';
else echo '<a href="/?act=logout">Sign Out</a><br />';
?>