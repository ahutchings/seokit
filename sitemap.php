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
<form action="sitemap.php" method="get">
	<label>Sitemap URL</label>
	<input name="url" size="45" value="http://www.site.com/sitemap.xml" type="text" class="text" />
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
    $result = MYSQL_QUERY("SELECT domain FROM domains WHERE domain='$domain' LIMIT 1");

    if (!$row = mysql_fetch_array($result)){
        $pr = "$scriptlocation/getpr.php?url=$domain";
        $pr = @file_get_contents($pr);

        mysql_query("INSERT INTO domains VALUES('','$domain','$pr')") or die(mysql_error());
    }

    echo "$feedurl being spidered.........<br>\n";

    $counter = 0;
    $type = 0;
    $tag = "";
    $itemInfo = array();
    $channelInfo = array();

    function opening_element($xmlParser, $name, $attribute){

        global $tag, $type;

        $tag = $name;

        if($name == "URLSET"){
            $type = 1;
        }
        else if($name == "URL"){
            $type = 2;
        }

    }//end opening element

    function closing_element($xmlParser, $name){

        global $tag, $type, $counter;

        $tag = "";
        if($name == "URL"){
            $type = 0;
            $counter++;
        }
        else if($name == "URLSET"){
            $type = 0;
        }
    }//end closing_element

    function c_data($xmlParser, $data){

        global $tag, $type, $channelInfo, $itemInfo, $counter;

        $data = trim(htmlspecialchars($data));

        if($tag == "TITLE" || $tag == "DESCRIPTION" || $tag == "LOC" || $tag == "PUBDATE"){
            if($type == 1){

                $channelInfo[strtolower($tag)] = $data;

            }//end checking channel
            else if($type == 2){

                $itemInfo[$counter][strtolower($tag)] .= $data;

            }//end checking for item
        }//end checking tag
    }//end cdata funct

    $xmlParser = xml_parser_create();

    xml_parser_set_option($xmlParser, XML_OPTION_CASE_FOLDING, TRUE);
    xml_parser_set_option($xmlParser, XML_OPTION_SKIP_WHITE, TRUE);

    xml_set_element_handler($xmlParser, "opening_element", "closing_element");
    xml_set_character_data_handler($xmlParser, "c_data");

    $fp = file($feedurl);
    if (!$fp){
        echo "Cannot connect to $feedurl";
        exit();
    }
    foreach ($fp as $line){
        if(!xml_parse($xmlParser, $line)){
            die("Could not parse file.");
        }
    }

    foreach ($itemInfo as $items){
        $url = $items['loc'];

        $result = MYSQL_QUERY("SELECT url FROM urls WHERE url='$url' LIMIT 1");

        if (!$row=mysql_fetch_array($result)){
            $pr="$scriptlocation/getpr.php?url=$url";
            $pr=@file_get_contents($pr);

            if (mysql_query("INSERT INTO urls VALUES('','$url','','0','','$pr')") or die(mysql_error())){
                echo "<a href=\"$url\">$url</a> was added!<br />\n";
            }

        }
        else
        {
            echo "<a href=\"$url\">$url</a> is already listed <BR>\n";
        }

    }
}

include 'footer.php';

?>