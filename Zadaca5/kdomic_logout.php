<?php session_start(); ?>
<?php
	if(isset($_SESSION['user_id']))
		unset($_SESSION['user_id']);
	header('Location: kdomic_index.php');
    exit();
?>