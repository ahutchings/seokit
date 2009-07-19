<?php include 'header.php' ?>

<?php if (!isset($_GET['url']) || empty($_GET['url'])): ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">
                    <h2>Add a single web page to the database</h2>

                    <p>If you would like to input just one webpage then enter it in the form
                    below.</p>

                    <p><b>Note:</b> if you spider a site using this method the page titles
                    will not be added to the database like they will be if you use <a
                    	href="/site/create">this method</a>.</p>

                    <form action="/site/from-page" id="add-url" method="get">
                    	<label>Web page URL</label>
                    	<input name="url" size="45" id="url" type="text" class="text" />
                    	<input value="Submit" type="submit" />
                    </form>
                </div></div>
            </div>
<?php else: ?>
	<?php
    $url = mysql_escape_string($_GET["url"]);
    $url = str_replace('http://', '', $url);
    $url = "http://$url";

    $result = mysql_query("SELECT url FROM page WHERE url='$url' LIMIT 1");

    if (!$row = mysql_fetch_array($result)){

        $pagerank = Google::get_pagerank($url);

        $db->exec("INSERT INTO page VALUES('','$url','$title','0','','$pagerank')");
    }

    $today        = date("Y-m-d");
    $inlink_count = Yahoo::get_inlink_count(array('query' => $url));

    $db->exec("UPDATE page SET updated_at = '$today', inlink_count = '$inlink_count' WHERE url = '$url' LIMIT 1");

    $domain = parse_url($url, PHP_URL_HOST);
    $result = mysql_query("SELECT domain FROM site WHERE domain = '$domain' LIMIT 1");

    if (!$row = mysql_fetch_array($result)) {

        $pagerank = Google::get_pagerank($domain);

        $db->exec("INSERT INTO site VALUES('', '$domain', '$pagerank')");
    }
endif; ?>

<?php include 'footer.php' ?>
