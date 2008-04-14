<rss version="2.0"><channel><title>heer</title><link>http://markkit.net/heer/</link><description>Comodity opensource social bookmarking</description>
<?php
	require '../../lib/link.php';

	Link::set_db('sqlite:../../heer.db');

	$links = Link::select();
	foreach ($links as $l) {
		echo $l->toRSSitem();
	}
?>
</channel>
</rss>
