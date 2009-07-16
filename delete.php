<?php

include 'config.inc.php';

$domain  = $_GET["domain"];
$confirm = $_GET["confirm"];

$domain = mysql_escape_string($domain);

if($confirm != "yes"){
    include 'header.php';
    echo "<h2>Are you sure?</h2>";
    echo "Are you 100% sure you want to delete all the urls for $domain?<BR><a href=\"delete.php?domain=$domain&confirm=yes\">Yes</a> <a href=\"index.php\">No</a>";

    include 'footer.php';
} else {

    $result = MYSQL_QUERY("DELETE FROM linkanalysis_domains WHERE domain='$domain' LIMIT 1");

    $result2 = MYSQL_QUERY("DELETE FROM linkanalysis_urls WHERE url LIKE '%$domain%'");
    $result3 = MYSQL_QUERY("DELETE FROM linkanalysis_linkdata WHERE url LIKE '%$domain%'");

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: index.php");
    exit();
}
