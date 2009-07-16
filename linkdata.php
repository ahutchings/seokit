<?php

include 'config.inc.php';

$refresh = $_GET["refresh"];
$url     = mysql_escape_string($_GET["url"]);
$orderby = $_GET["orderby"];

include 'header.php';

if ($refresh == "yes") {
    ?>
<h2>Spider results</h2>
    <?php

    echo "Links to $url are being found.........<br>\n";


    $counter = 0;
    $type = 0;
    $tag = '';
    $itemInfo = array();
    $channelInfo = array();

    function opening_element($xmlParser, $name, $attribute)
    {
        global $tag, $type;

        $tag = $name;

        if ($name == "RESULTSET"){
            $type = 1;
        } elseif ($name == "RESULT"){
            $type = 2;
        }

    }

    function closing_element($xmlParser, $name)
    {
        global $tag, $type, $counter;

        $tag = '';

        if ($name == "RESULT"){
            $type = 0;
            $counter++;
        } elseif ($name == "RESULTSET"){
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
        $request = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=FUH9aZjV34GSWglIPsIhtcRNWA3_oTLSJDq51iBY8_P7.ykFyeZOcLoH.Hz4AiI-&query=';
        $request .= $url;
        $request .= '&results=100&start=';
        $request .= $start;

        $xmlParser = xml_parser_create();

        xml_parser_set_option($xmlParser, XML_OPTION_CASE_FOLDING, TRUE);
        xml_parser_set_option($xmlParser, XML_OPTION_SKIP_WHITE, TRUE);

        xml_set_element_handler($xmlParser, "opening_element", "closing_element");
        xml_set_character_data_handler($xmlParser, "c_data");

        $fp = '';
        $fp = @file($request);

        if (!$fp) {
            echo "Cannot connect to Yahoo, you might have used more than 5000 queries today?";
            include 'footer.php';
            exit();
        }

        foreach ($fp as $line) {
            if (!xml_parse($xmlParser, $line)) {
                echo "Cannot parse xml file";
                include 'footer.php';
                exit();
            }
        }

        foreach ($itemInfo as $items) {
            $linking_page = $items['url'];
            $linking_page_title = $items['title'];
            $linking_page_title = mysql_escape_string($linking_page_title);
            $linking_page = mysql_escape_string($linking_page);

            $result = MYSQL_QUERY("SELECT linking_page FROM linkdata WHERE linking_page='$linking_page' LIMIT 1");

            if (!$row=mysql_fetch_array($result)){
                $pr="$scriptlocation/getpr.php?url=$domain";
                $pr=@file_get_contents($pr);
                if (mysql_query("INSERT INTO linkdata VALUES('','$url','$linking_page','$linking_page_title','$pr','0')") or die(mysql_error())){
                    echo "Link found at $pr <a href=\"$linking_page\">$linking_page_title</a><br />\n";

                }

            }
            else
            {
                $pr = "$scriptlocation/getpr.php?url=$linking_page";
                $pr = @file_get_contents($pr);
                $db->exec("UPDATE linkdata SET linking_page_inlinks='$incoming_links',linking_page_pr='$pr' WHERE linking_page='$linking_page' LIMIT 1");
                echo "Link data updated <a href=\"$linking_page\">$linking_page_title</a><br>\n";

            }
            $update = "$scriptlocation/getlinks.php?linking_page=$linking_page";
            $update = file_get_contents($update);

        }

        $start += 100;
    }


} else {
    if ($orderby == '') {
        $orderby = "linking_page_inlinks";
    }

    ?>
    <h2>Links to <?php echo $url ?></h2>
    <p><a href="linkdata.php?url=<?php echo $url ?>&amp;refresh=yes">Click here to refresh this data using Yahoo</a></p>
    <table>
        <thead>
            <tr>
            	<td></td>
            	<td><a href="linkdata.php?url=<?php echo $url ?>&orderby=linking_page_inlinks">Links</a></td>
            	<td><a href="linkdata.php?url=<?php echo $url ?>&orderby=linking_page_pr">PR</a></td>
            	<td colspan="4"></td>
        	</tr>
    	</thead>
    	<tbody>

	<?php
    $query = "SELECT * FROM linkdata WHERE url='$url' ORDER BY $orderby DESC LIMIT 1000";

    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {

        echo "<tr><td width=\"410\" bgcolor=\"$bg\"><a href=\"$row[linking_page]\" title=\"$row[linking_page]\">$row[linking_page_title]</a></td><td width=\"40\" bgcolor=\"$bg\"><a href=\"https://siteexplorer.search.yahoo.com/advsearch?p=$row[linking_page]&bwm=i&bwmo=d&bwmf=u\" target=\"_blank\">$row[linking_page_inlinks] links</a></td>";
        echo "<td width=\"40\"><img src=\"images/pr$row[linking_page_pr].gif\" alt=\"PageRank $row[linking_page_pr]\" title=\"PageRank $row[linking_page_pr]\"></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[linking_page]&engine=g\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/g.gif\" alt=\"Check ranking on Google\" width=\"16\" height=\"16\" title=\"Check ranking on Google\" border=\"0\"></a></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[linking_page]&engine=y\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/y.gif\" alt=\"Check ranking on Yahoo\" width=\"16\" height=\"16\" title=\"Check ranking on Yahoo\" border=\"0\"></a></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[linking_page]&engine=m\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/m.gif\" alt=\"Check ranking on MSN\" width=\"16\" height=\"16\" title=\"Check ranking on MSN\" border=\"0\"></a></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"update.php?linking_page=$row[linking_page]&url=$url\"><img src=\"http://www.blogstorm.co.uk/images/refresh.jpeg\" alt=\"Update link count for $row[linking_page]\" title=\"Update link count for $row[linking_page]\" border=\"0\"></a></td>";
        echo "</tr>\n";


    }
    echo "</tbody></table>";
}

include 'footer.php';
