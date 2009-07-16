<?php

include 'config.inc.php';

$linking_page = mysql_escape_string($_GET["linking_page"]);
$url          = mysql_escape_string($_GET["url"]);
$domain       = mysql_escape_string($_GET["domain"]);

$today = date("Y-m-d");

if (!empty($url)) {
    $request = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=';
    $request .= $yahoo_api_key;
    $request .= '&query=';
    $request .= $url;
    $request .= '&output=php';
    $request .= '&omit_inlinks=domain';
    $output = unserialize(file_get_contents($request));
    $incoming_links=$output[ResultSet][totalResultsAvailable];

    if (ctype_digit($incoming_links)) {

        $result3 = $db->exec("UPDATE urls SET checkdate='$today',links='$incoming_links' WHERE url='$url' LIMIT 1");
        echo "$incoming_links links to $url";
    }
}

if (!empty($linking_page)){

    $request = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=';
    $request .= $yahoo_api_key;
    $request .= '&query=';
    $request .= $linking_page;
    $request .= '&output=php';
    $request .= '&omit_inlinks=domain';
    $output = unserialize(file_get_contents($request));
    $incoming_links = $output['ResultSet']['totalResultsAvailable'];

    if (ctype_digit($incoming_links)) {

        $result3 = $db->exec("UPDATE linkdata SET linking_page_inlinks='$incoming_links' WHERE linking_page='$linking_page' LIMIT 1");
        echo "$incoming_links links to $url";
    }
}
