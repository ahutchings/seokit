<?php

include 'config.inc.php';

$confirm = $_GET["confirm"];

$domain = mysql_escape_string($_GET["domain"]);

if ($confirm != "yes"){
    include 'header.php';
    ?>
    <h2>Are you sure?</h2>
    <p>Are you sure you want to delete all the urls for $domain?<br>
    <a href="delete.php?domain=<?php echo $domain?>&amp;confirm=yes">Yes</a> <a href="/">No</a></p>
    <?php
    include 'footer.php';
} else {
    $db->exec("DELETE FROM domain WHERE domain='$domain' LIMIT 1");
    $db->exec("DELETE FROM urls WHERE url LIKE '%$domain%'");
    $db->exec("DELETE FROM linkdata WHERE url LIKE '%$domain%'");

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: index.php");
    exit();
}
