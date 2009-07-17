<?php include 'header.php' ?>

<?php if (!isset($_GET['url']) || empty($_GET['url'])): ?>
    <h2>Add a single web page to the database</h2>

    <p>If you would like to input just one webpage then enter it in the form
    below.</p>

    <p><b>Note:</b> if you spider a site using this method the page titles
    will not be added to the database like they will be if you use <a
    	href="/site/create">this method</a>.</p>

    <form action="addurl.php" id="add-url" method="get">
    	<label>Web page URL</label>
    	<input name="url" size="45" id="url" type="text" class="text" />
    	<input value="Submit" type="submit" />
    </form>
<?php else: ?>
	<?php
    $url = mysql_escape_string($_GET["url"]);
    $url = str_replace('http://', '', $url);
    $url = "http://$url";

    $result = mysql_query("SELECT url FROM urls WHERE url='$url' LIMIT 1");

    if (!$row = mysql_fetch_array($result)){

        $pr = Google::get_pagerank($url);

        if ($db->exec("INSERT INTO urls VALUES('','$url','$title','0','','$pr')")) {
            echo "<h2>Page added</h2> <br> <a href=\"$url\">$url</a> was added to the database<br />\n";
        }

    } else {
        echo "<h2>Results</h2> <br> <a href=\"$url\">$url</a> is already listed in the database<br>\n";
    }

    $today = date("Y-m-d");

    $incoming_links = Yahoo::get_inlink_count(array('query' => $url));

    if (ctype_digit($incoming_links)) {
        $db->exec("UPDATE urls SET checkdate='$today',links='$incoming_links' WHERE url='$url' LIMIT 1");
        echo "$incoming_links links to $url";
    }

    $domain = parse_url($url, PHP_URL_HOST);
    $result = mysql_query("SELECT domain FROM site WHERE domain='$domain' LIMIT 1");

    if (!$row = mysql_fetch_array($result)) {

        $pr = Google::get_pagerank($domain);

        $db->exec("INSERT INTO site VALUES('','$domain','$pr')");
    }
endif; ?>

<?php include 'footer.php' ?>
