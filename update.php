<?php

$linking_page = mysql_escape_string($_GET["linking_page"]);
$url          = mysql_escape_string($_GET["url"]);

if (!empty($linking_page)) {

    $incoming_links = Yahoo::get_inlink_count(array('query' => $linking_page));
    $pr             = Google::get_pagerank($linking_page);

    $db->exec("UPDATE linkdata SET linking_page_inlinks='$incoming_links',linking_page_pr='$pr' WHERE linking_page='$linking_page' LIMIT 1");

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /site/page?url=$url");
    exit();

} elseif (!empty($url)) {

    $today          = date("Y-m-d");
    $incoming_links = Yahoo::get_inlink_count(array('query' => $url));
    $pr             = Google::get_pagerank($url);

    $db->exec("UPDATE urls SET checkdate='$today',links='$incoming_links',pr='$pr' WHERE url='$url' LIMIT 1");

    $domain = parse_url($url, PHP_URL_HOST);

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /site/?domain=$domain&checked=$url");
    exit();
}
