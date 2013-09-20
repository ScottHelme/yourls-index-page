<?php
// pull in YOURLS system and configuration
require_once(dirname(__FILE__).'/includes/load-yourls.php' );

// connect to db
$db = yourls_db_connect();

// get all URLs
$urls = null;
if ($db) {
    $urls = $db->get_results("SELECT `keyword`,`url`,`title`,`timestamp`,`clicks` FROM `" . YOURLS_DB_TABLE_URL . "` ORDER BY `timestamp` DESC");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>scotthel.me - short url service</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/index/index.css">
    <link rel="stylesheet" type="text/css" href="/index/tablesorter/style.css">
    <meta name="robots" content="index,follow">
</head>
<body>

<h1>scotthel.me</h1>
<h2>scott helme's short url service</h2>

<div id="header">
	<ul class="subnav">
		<li><a rel="me" href="http://www.scotthelme.co.uk">click here to visit my blog</a></li>
	</ul>
</div>


<?php //output YOURLS statistics
	if (!empty($urls)){
		$totalLinks = 0;
		$totalClicks = 0;
		
		foreach ($urls as $url) {
			$totalLinks += 1;
			$totalClicks += $url->clicks;
		}
		
		echo "<center>So far I'm tracking " . $totalLinks . " links with " . $totalClicks . " clicks!</center>";
?>

<?php if (!empty($urls)) : ?>
<table id="urls" class="tablesorter" align="center">

	<thead>
		<tr>
			<th>code</th>
			<th>original url</th>
			<th>created</th>
			<th>clicks</th>
		</tr>
	</thead>

	<tbody>
		<?php
		foreach ($urls as $url) {
			echo "<tr>\n";
			
			$title = $url->title;

			$short_code = $url->keyword;
			$short_link = YOURLS_SITE . "/" . $short_code;
			
			$long_code = $url->url;
			$long_code = preg_replace("#^http://#" ,       "", $long_code);
			$long_code = preg_replace("#^www.#"    ,       "", $long_code);
			$long_code = preg_replace("#/blog(..)#"    ,"/$1", $long_code);
			$long_code = preg_replace("#/archives#",    "...", $long_code);
			$long_code = preg_replace("#/$#"       ,       "", $long_code);
			if(strlen($long_code) > 80) {
				$long_code = substr_replace($long_code, "...", 80 - 3);
			}
			
			$short_date = substr($url->timestamp, 0, 10);
			
			echo "<td class=\"code\">";
			echo "<a rel=\"follow\" title=\"" . $title . "\" target=\"blank\" href=\"" . $short_link . "\">" . $short_code . "</a>";
			echo "</td>\n";

			echo "<td>";
			echo "<a rel=\"follow\" title=\"" . $title . "\" target=\"blank\" href=\"" . $url->url . "\">" . $long_code . "</a>";
			echo "</td>\n";
			
			echo "<td>";
			echo $short_date;
			echo "</td>\n";

			echo "<td class=\"clicks\">";
			echo $url->clicks;
			echo "</td>\n";
			
			echo "</tr>\n";
		}
		?>
	</tbody>

</table>
<?php endif ?>

<div id="footer">
	scotthel.me is built with <a target="_blank" href="http://yourls.org">YOURLS</a>, <a target="_blank" href="http://jquery.com">jQuery</a> and <a target="_blank" href="http://tablesorter.com">Tablesorter</a>
	<br>
	&copy; copyright <?= date('Y') ?> <a target="_blank" rel="me" href="http://www.scotthelme.co.uk">scott helme</a>
</div>

<script type="text/javascript" src="/js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function(){ 
	$("#urls").tablesorter({
		sortList: [[2,1] , [0, 1]]
	}); 
}); 
// -->
</script>

</body>
</html>
