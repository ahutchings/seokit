<?php include 'header.php' ?>

<?php if (!isset($_GET['url']) || empty($_GET['url'])): ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">
                    <h2>Spider an XML sitemap for Pages</h2>

                    <p>If you would like to input a lot of pages it is sometimes better to
                    use an XML sitemap rather than relying on Yahoo! to return indexed pages.</p>

                    <p><b>Note:</b> if you spider a site using this method the page titles
                    will not be added to the database like they will be if you use <a
                    	href="/site/create">this method</a>.</p>

                    <form action="/site/from-sitemap" id="sitemap" method="get">
                    	<label for="url">Sitemap URL</label>
                    	<input name="url" size="45" id="url" type="text" class="text" />
                    	<input value="Submit" type="submit" />
                    </form>
                </div></div>
            </div>
<?php else: ?>
	<h2>Spider results</h2>

    <p><?php echo $_GET['url'] ?> is being spidered...</p>

    <?php
    $domain = parse_url($_GET['url'], PHP_URL_HOST);

    $result = mysql_query("SELECT domain FROM site WHERE domain='$domain' LIMIT 1");

    if (!$row = mysql_fetch_array($result)){

        $pagerank = Google::get_pagerank($domain);

        $db->exec("INSERT INTO site VALUES('','$domain','$pagerank')");
    }

    $counter = 0;
    $type = 0;
    $tag = '';
    $itemInfo = array();
    $channelInfo = array();

    function opening_element($xmlParser, $name, $attribute)
    {
        global $tag, $type;

        $tag = $name;

        if ($name == "URLSET") {
            $type = 1;
        } elseif ($name == "URL") {
            $type = 2;
        }
    }

    function closing_element($xmlParser, $name)
    {
        global $tag, $type, $counter;

        $tag = "";

        if ($name == "URL") {
            $type = 0;
            $counter++;
        } elseif ($name == "URLSET") {
            $type = 0;
        }
    }

    function c_data($xmlParser, $data)
    {
        global $tag, $type, $channelInfo, $itemInfo, $counter;

        $data = trim(htmlspecialchars($data));

        if (in_array($tag, array('TITLE', 'DESCRIPTION', 'LOC', 'PUBDATE'))) {
            if ($type == 1) {
                $channelInfo[strtolower($tag)] = $data;
            } elseif ($type == 2) {
                $itemInfo[$counter][strtolower($tag)] .= $data;
            }
        }
    }

    $xmlParser = xml_parser_create();

    xml_parser_set_option($xmlParser, XML_OPTION_CASE_FOLDING, TRUE);
    xml_parser_set_option($xmlParser, XML_OPTION_SKIP_WHITE, TRUE);

    xml_set_element_handler($xmlParser, "opening_element", "closing_element");
    xml_set_character_data_handler($xmlParser, "c_data");

    $fp = file($_GET['url']);

    if (!$fp) {
        echo "Cannot connect to " . $_GET['url'];
        exit();
    }

    foreach ($fp as $line){
        if (!xml_parse($xmlParser, $line)) {
            die("Could not parse file.");
        }
    }

    foreach ($itemInfo as $items) {
        $url = $items['loc'];

        $page_exists = DB::connect()->query("SELECT COUNT(1) FROM page WHERE url = '$url'")->fetchColumn();

        if (!$page_exists){
            DB::connect()->exec("INSERT INTO page VALUES('','$url','')");

            // @todo update page metrics
        }
    }

endif;

?>

<?php include 'footer.php' ?>
