<?php

$linking_page = mysql_escape_string($_GET["linking_page"]);
$url          = mysql_escape_string($_GET["url"]);

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

    echo "<meta http-equiv=\"refresh\" content=\"10;url=/?domain=$domain\">";
    include 'footer.php';
}
