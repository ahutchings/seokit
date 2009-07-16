<?php

include 'config.inc.php';
include 'header.php';

if (!isset($_GET['url']) || empty($_GET['url'])) {
    ?>
<h2>Spider an xml sitemap for URL's</h2>
<p>If you would like to input a lot of URL's it is sometimes better to
use an xml sitemap rather than relying on Yahoo to return indexed pages.

<p><b>Note:</b> if you spider a site using this method the page titles
will not be added to the database like they will be if you use <a
	href="addsite.php">this method</a>.
<p>Enter the xml sitemap location in the box below:
<form action="sitemap.php" id="sitemap" method="get">
	<label>Sitemap URL</label>
	<input name="url" size="45" id="url" type="text" class="text" />
	<input value="Submit" type="submit" />
</form>
    <?php
} else {
    ?>
<h2>Spider results</h2>
    <?php
    $feedurl = mysql_escape_string($_GET["url"]);
    $str = explode('/',$feedurl);
    $domain = "$str[2]";
    $result = mysql_query("SELECT domain FROM domain WHERE domain='$domain' LIMIT 1");

    if (!$row = mysql_fetch_array($result)){

        $pr = Google::get_pagerank($domain);

        $db->exec("INSERT INTO domain VALUES('','$domain','$pr')");
    }

    echo "$feedurl is being spidered.........<br>\n";

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

    $fp = file($feedurl);
    if (!$fp) {
        echo "Cannot connect to $feedurl";
        exit();
    }
    foreach ($fp as $line){
        if (!xml_parse($xmlParser, $line)) {
            die("Could not parse file.");
        }
    }

    foreach ($itemInfo as $items) {
        $url = $items['loc'];

        $result = mysql_query("SELECT url FROM urls WHERE url='$url' LIMIT 1");

        if (!$row = mysql_fetch_array($result)){

            $pr = Google::get_pagerank($url);

            if (mysql_query("INSERT INTO urls VALUES('','$url','','0','','$pr')") or die(mysql_error())){
                echo "<a href=\"$url\">$url</a> was added!<br />\n";
            }

        } else {
            echo "<a href=\"$url\">$url</a> is already listed <br>\n";
        }

    }
}

include 'footer.php';
