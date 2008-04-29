<?php
	
	require '../../../lib/link.php';
	require '../../../lib/id.php';
	require '../../../lib/vote.php';

	Link::set_db('sqlite:../../../heer.db');
	Vote::set_db('sqlite:../../../heer.db');
	ID::set_seed(file_get_contents('../../../seed'));

	$url         = $_GET['u'];
	$title       = $_GET['t'];
	$bookmarklet = new ID($_GET['i']);
	$vote        = new Vote($url, $bookmarklet);

	$link = new Link($url, $title);
	if ($bookmarklet->isAuthentic() && $vote->save()) {
		$link->save();
	}

	header("Location: $url");
