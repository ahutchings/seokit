<?php

include 'config.inc.php';
include 'header.php';

if (!isset($_GET['url']) || empty($_GET['url'])) {
    ?>
<h2>Add a single web page to the database</h2>

<p>If you would like to input just one webpage then enter it in the form
below.
<p><b>Note:</b> if you spider a site using this method the page titles
will not be added to the database like they will be if you use <a
	href="addsite.php">this method</a>.
<form action="addurl.php" method="get">Web page URL: <input name="url"
	size="45" value="http://www.site.com/single-page" type="text" class="text" /><input
	value="Submit" type="submit" /></form>
    <?php

} else {
    $url = mysql_escape_string($_GET["url"]);
    $url = str_replace("http://", "", $url);
    $url = "http://$url";

    $result = MYSQL_QUERY("SELECT url FROM linkanalysis_urls WHERE url='$url' LIMIT 1");

    if (!$row = mysql_fetch_array($result)){

        $pr = "$scriptlocation/getpr.php?url=$url";
        $pr = @file_get_contents($pr);

        if (mysql_query("INSERT INTO linkanalysis_urls VALUES('','$url','$title','0','','$pr')") or die(mysql_error())){
            echo "<h2>Page added</h2> <BR> <a href=\"$url\">$url</a> was added to the database<br />\n";
        }

    } else {
        echo "<h2>Results</h2> <BR> <a href=\"$url\">$url</a> is already listed in the database<BR>\n";
    }
    $update = "$scriptlocation/getlinks.php?url=$url";
    $update = file_get_contents($update);

    $str    = explode('/',$url);
    $domain = "$str[2]";
    $result = MYSQL_QUERY("SELECT domain FROM linkanalysis_domains WHERE domain='$domain' LIMIT 1");

    if (!$row = mysql_fetch_array($result)){
        $pr = "$scriptlocation/getpr.php?url=$domain";
        $pr = @file_get_contents($pr);
        if (mysql_query("INSERT INTO linkanalysis_domains VALUES('','$domain','$pr')") or die(mysql_error())){

        }
    }
}

include 'footer.php';
