<?php include 'header.php' ?>

<?php if (!isset($_GET['url']) || empty($_GET['url'])): ?>
    <h2>Add a site to be analysed</h2>

    <p>To input a site just enter the url in the box below and the script
    will store all the known url's in the database ready for analysis.</p>

    <form action="/domain/create" id="add-site" method="get">
    	<label>Site URL</label>
    	<input name="url" id="url" size="45" type="text" class="text" />
    	<input value="Submit" type="submit" />
    </form>

    <p>The script captures data using the "Pages in Site" feature of
    <a href="https://siteexplorer.search.yahoo.com">Yahoo Site Explorer</a>.</p>

    <p>You can add a site that's already in the database if you need to for
    any reason, we won't store duplicate urls.</p>

<?php else: ?>
	<h2>Spider results</h2>
    <?php

    echo $_GET['url'] . " is being spidered.........<br>\n";

    $counter = 0;
    $type = 0;
    $tag = '';
    $itemInfo = array();
    $channelInfo = array();

    function opening_element($xmlParser, $name, $attribute)
    {
        global $tag, $type;

        $tag = $name;

        if ($name == "RESULTSET") {
            $type = 1;
        } elseif ($name == "RESULT") {
            $type = 2;
        }
    }

    function closing_element($xmlParser, $name)
    {
        global $tag, $type, $counter;

        $tag = '';

        if ($name == "RESULT") {
            $type = 0;
            $counter++;
        } elseif ($name == "RESULTSET") {
            $type = 0;
        }
    }

    function c_data($xmlParser, $data)
    {
        global $tag, $type, $channelInfo, $itemInfo, $counter;

        $data = trim(htmlspecialchars($data));

        if (in_array($tag, array('TITLE', 'DESCRIPTION', 'URL', 'PUBDATE'))) {
            if ($type == 1){
                $channelInfo[strtolower($tag)] = $data;
            } elseif ($type == 2) {
                $itemInfo[$counter][strtolower($tag)] .= $data;
            }
        }
    }

    $start = 1;
    while ($start <= 1000) {
        $request = 'http://search.yahooapis.com/SiteExplorerService/V1/pageData?appid=';
        $request .= Options::get('yahoo_api_key');
        $request .= '&query=';
        $request .= $url;
        $request .= '&results=100&start=';
        $request .= $start;

        $xmlParser = xml_parser_create();

        xml_parser_set_option($xmlParser, XML_OPTION_CASE_FOLDING, TRUE);
        xml_parser_set_option($xmlParser, XML_OPTION_SKIP_WHITE, TRUE);

        xml_set_element_handler($xmlParser, "opening_element", "closing_element");
        xml_set_character_data_handler($xmlParser, "c_data");

        $fp = @file($request);

        if (!$fp) {
            echo "Cannot connect to Yahoo, you might have used more than 5000 queries today?";
            include 'footer.php';
            exit();
        }
        foreach ($fp as $line) {
            if (!xml_parse($xmlParser, $line)){
                echo "Cannot parse xml file";
                include 'footer.php';
                exit();
            }
        }

        foreach ($itemInfo as $items) {
            $url = $items['url'];
            $title = $items['title'];
            $title = mysql_escape_string($title);
            $q = "SELECT url FROM urls WHERE url='$url' LIMIT 1";

            if (!$row = $db->query($q)->fetch()){

                $pr = Google::get_pagerank($url);
                if ($db->exec("INSERT INTO urls VALUES('','$url','$title','0','','$pr')")){
                    echo "<a href=\"$url\">$url</a> was added!<br />\n";
                    $update = "$scriptlocation/getlinks.php?url=$url";
                    $update = file_get_contents($update);
                }

            } else {
                echo "<a href=\"$url\">$url</a> is already listed <br>\n";
                $update = "$scriptlocation/getlinks.php?url=$url";
                $update = file_get_contents($update);
            }

        }

        $start += 100;
    }

endif;

include 'footer.php';
