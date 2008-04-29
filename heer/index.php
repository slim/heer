<?php
  $hereUrl   = "http://". $_SERVER['SERVER_NAME'] ."/". $_SERVER['REQUEST_URI'];
  $submitUrl = $hereUrl .'/link/submit/';
  $rssUrl    = $hereUrl .'/rss/';
  $bookmarkletUrl = $hereUrl ."/bookmarklet/?u=$submitUrl&t=heer";
?>
<h1>Welcome heer.</h1>
<p>heer bookmarklet → <?php echo file_get_contents($bookmarkletUrl); ?></p>
<p><a href="<?php echo $rssUrl; ?>">heer rss</a></p>
