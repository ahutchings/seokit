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
	$db = DB::connect();

    $url = mysql_escape_string($_GET["url"]);
    $url = str_replace('http://', '', $url);
    $url = "http://$url";

    $page_exists = $db->query("SELECT COUNT(1) FROM page WHERE url= '$url'")->fetchColumn();

    if (!$page_exists){
        // create the page
        $db->exec("INSERT INTO page VALUES('','$url','$title')");

        // @todo update page metrics
    }

    $domain      = parse_url($url, PHP_URL_HOST);
    $site_exists = $db->query("SELECT COUNT(1) FROM site WHERE domain = '$domain'")->fetchColumn();

    if (!$site_exists) {
        $pagerank = Google::get_pagerank($domain);

        $db->exec("INSERT INTO site VALUES('', '$domain', '$pagerank')");
    }
endif; ?>

<?php include 'footer.php' ?>
