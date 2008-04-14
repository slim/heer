<?php
	
	require '../../../lib/link.php';

	Link::set_db('sqlite:../../../heer.db');

	$url   = $_GET['u'];
	$title = $_GET['t'];

	$link = new Link($url, $title);
	$link->save();

	header("Location: $url");
