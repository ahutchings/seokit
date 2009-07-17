<?php

$linking_page = mysql_escape_string($_GET["linking_page"]);
$url          = mysql_escape_string($_GET["url"]);
$domain       = mysql_escape_string($_GET["domain"]);

$today = date("Y-m-d");

if (!empty($linking_page)) {

    $incoming_links = Yahoo::get_inlink_count(array('query' => $linking_page));

    if (ctype_digit($incoming_links)) {

        $pr = Google::get_pagerank($linking_page);

        $db->exec("UPDATE linkdata SET linking_page_inlinks='$incoming_links',linking_page_pr='$pr' WHERE linking_page='$linking_page' LIMIT 1");
    }

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: linkdata.php?url=$url");
    exit();

} else {

    if (!empty($url)){

        $incoming_links = Yahoo::get_inlink_count(array('query' => $url));

        if (ctype_digit($incoming_links)) {

            $pr = Google::get_pagerank($url);

            $db->exec("UPDATE urls SET checkdate='$today',links='$incoming_links',pr='$pr' WHERE url='$url' LIMIT 1");
        }
        $str = explode('/',$url);
        $domain = "$str[2]";

        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /?domain=$domain&checked=$url");
        exit();

    }

    if (!empty($domain) && empty($url)){
        include 'header.php';
        echo "<h2>Getting link data</h2><p>This might take a while but you will be <a href=\"/?domain=$domain\">sent here when it finishes</a>.";

        $domain1 = "http://$domain";
        $q       = "SELECT * FROM urls WHERE checkdate !='$today' AND url LIKE '$domain1%' ORDER BY id DESC LIMIT 5000";

        while ($row = DB::connect()->query($q)->fetch()) {

            $url = $row['url'];

            $incoming_links = Yahoo::get_inlink_count(array('query' => $url));

            if (ctype_digit($incoming_links)) {

                $pr = Google::get_pagerank($url);

                $db->exec("UPDATE urls SET checkdate='$today',links='$incoming_links',pr='$pr' WHERE url='$url' LIMIT 1");
                $db->exec("UPDATE site SET pr='$pr' WHERE domain='$domain' LIMIT 1");

                echo "$incoming_links links to $url <br> \n";
            } else {
                echo "Error getting link count for $url <br> \n";
            }
        }
    }

    echo "<meta http-equiv=\"refresh\" content=\"10;url=/?domain=$domain\">";
    include 'footer.php';
}
