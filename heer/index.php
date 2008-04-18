<?php
  $hereUrl   = "http://". $_SERVER['SERVER_NAME'] ."/". $_SERVER['REQUEST_URI'];
  $submitUrl = $hereUrl .'/link/submit/';
  $rssUrl    = $hereUrl .'/rss/';
?>
<h1>Welcome heer.</h1>
<p>heer bookmarklet → <a href="javascript:location.href='<?php echo $submitUrl; ?>?u='+encodeURIComponent(location.href)+'&t='+encodeURIComponent(document.title)">heer</a></p>
<p><a href="<?php echo $rssUrl; ?>">heer rss</a></p>
