<?php
include 'config.inc.php';

$tables = array();
$tbl  = "linkanalysis_domains";
$tbl2 = "linkanalysis_urls";
$tbl3 = "linkanalysis_linkdata";

$q = @mysql_query("SHOW TABLES");
while ($r = @mysql_fetch_array($q)) { $tables[] = $r[0]; }
@mysql_free_result($q);
@mysql_close($link);
if (in_array($tbl, $tables)) {
    echo " table $tbl already exists <BR>";
}
else
{
    $result="CREATE TABLE linkanalysis_domains (id int(11) auto_increment, PRIMARY KEY(id), domain VARCHAR(255), pr int(2))";
    echo 'Creating table: \'linkanalysis_domains\'....<BR>';
    mysql_query($result) or die(mysql_error());
}

if (in_array($tbl2, $tables)) {
    echo " table $tbl2 already exists<BR>";
}
else
{
    $result="CREATE TABLE linkanalysis_urls (id int(11) auto_increment, PRIMARY KEY(id), url VARCHAR(255), title VARCHAR(255), links int(11),checkdate date,pr int(2))";
    echo 'Creating table: \'linkanalysis_urls\'....<BR>';
    mysql_query($result) or die(mysql_error());
}

if (in_array($tbl3, $tables)) {
    echo " table $tbl3 already exists<BR>";
}
else
{
    $result="CREATE TABLE linkanalysis_linkdata (id int(11) auto_increment, PRIMARY KEY(id), url VARCHAR(255), linking_page VARCHAR(255), linking_page_title VARCHAR(255), linking_page_pr int(2), linking_page_inlinks int(11))";
    echo 'Creating table: \'linkanalysis_linkdata\'....<BR>';
    mysql_query($result) or die(mysql_error());
}
echo "<BR><a href=\"index.php\">Setup completed</a>";

?>