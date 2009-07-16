<?php

include 'config.inc.php';

$domain = Domains::get(array('id' => $_GET['id']));

if (!isset($_GET['confirm']) || $_GET['confirm'] != "yes"){
    include 'header.php';
    ?>
    <h2>Are you sure?</h2>
    <p>Are you sure you want to delete all the urls for <?php echo $domain->domain ?>?<br>
    <a href="delete.php?id=<?php echo $domain->id ?>&amp;confirm=yes">Yes</a> <a href="/">No</a></p>
    <?php
    include 'footer.php';
} else {
    $db->exec("DELETE FROM domain WHERE id = {$domain->id} LIMIT 1");
    $db->exec("DELETE FROM urls WHERE url LIKE '%{$domain->domain}%'");
    $db->exec("DELETE FROM linkdata WHERE url LIKE '%{$domain->domain}%'");

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: index.php");
    exit();
}
