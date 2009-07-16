<?php

include 'config.inc.php';

$confirm = $_GET["confirm"];

$domain = mysql_escape_string($_GET["domain"]);

if ($confirm != "yes"){
    include 'header.php';
    echo "<h2>Are you sure?</h2>";
    echo "Are you 100% sure you want to delete all the urls for $domain?<BR><a href=\"delete.php?domain=$domain&confirm=yes\">Yes</a> <a href=\"index.php\">No</a>";

    include 'footer.php';
} else {

    $result  = MYSQL_QUERY("DELETE FROM domains WHERE domain='$domain' LIMIT 1");
    $result2 = MYSQL_QUERY("DELETE FROM urls WHERE url LIKE '%$domain%'");
    $result3 = MYSQL_QUERY("DELETE FROM linkdata WHERE url LIKE '%$domain%'");

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: index.php");
    exit();
}
