<?php
  	require "../../lib/id.php";
	ID::set_seed(file_get_contents('../../seed'));

  	$submitUrl = $_GET['u'];
  	$bookmarkletTitle = $_GET['t'] ? $_GET['t'] : "heer";
  	$bookmarkletID = new ID();
?>
<a href="javascript:location.href='<?php echo $submitUrl; ?>?u='+encodeURIComponent(location.href)+'&t='+encodeURIComponent(document.title)+'&i=<?php echo $bookmarkletID; ?>'"><?php echo $bookmarkletTitle; ?></a>
