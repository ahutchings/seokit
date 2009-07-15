<?php
	include('config.inc.php');
	include("header.php");
	
	$domain=$_GET["domain"];
	$orderby=$_GET["orderby"];
	$checked=$_GET["checked"];
	$domain=mysql_escape_string($domain);
	

	$tables = array();
	$tbl="linkanalysis_domains";
	
	$q = @mysql_query("SHOW TABLES");
    while ($r = @mysql_fetch_array($q)) { $tables[] = $r[0]; }
    @mysql_free_result($q);
    @mysql_close($link);
    if (in_array($tbl, $tables)) { 
	    
	    
	    
	    if($domain==""){
	?>
<h2>Competitive link analysis made easy</h2>
<p><img src="http://www.blogstorm.co.uk/images/tools-thumb.gif" alt="Link tool" align="left">
This tool is designed to find out exactly which pages on a website have the most links pointing to them.</p>
<p>In general the more links a website has the better it will rank in the search engines, if you can find a page on your competitors website with thousands of links then you can take inspiration from the ideas on that page and get some similar links yourself.</p>
<p>Yahoo gives 5,000 queries per day under a single API key/IP address which should be plenty. <a href="http://developer.yahoo.com/search/">More details</a>.
<p>Yahoo also only returns the first 1000 urls in a site so for large sites you might need to add the rest using the sitemap.xml spider.

<h2>Your domains</h2>
<table cellpadding="5" cellspacing="0" border="1">
<?php
 $query = "select * from linkanalysis_domains ORDER BY id ASC"; 

 $result=mysql_query($query);
 while ($row= mysql_fetch_array($result)) {
	 echo"<tr><td width=\"50\">$row[id]</td><td width=\"200\"><a href=\"index.php?domain=$row[domain]\">$row[domain]</a></td>";
	 echo"<td><img src=\"images/pr$row[pr].gif\" alt=\"PageRank $row[pr]\" title=\"PageRank $row[pr]\"></td><td width=\"30\"><a href=\"update.php?domain=$row[domain]\"><img src=\"http://www.blogstorm.co.uk/images/refresh.jpeg\" alt=\"Update all link counts for $row[domain]\" title=\"Update all link counts for $row[domain]\" border=\"0\"></a></td>";
	 echo"<td><a href=\"delete.php?domain=$row[domain]\">Delete entire domain</a></td></tr>\n";
	 
 }
 echo "</table><BR><a href=\"addsite.php\">Add a new domain</a>";

}
else
{
	echo"<h2>Results for $domain</h2>";
	echo"<table cellpadding=\"4\" cellspacing=\"0\" border=\"1\" width=\"100%\">";
	echo"<tr><td></td><td><a href=\"index.php?domain=$domain&orderby=links\">Links</a></td><td><a href=\"index.php?domain=$domain&orderby=pr\">PR</a></td><td colspan=\"5\"></td></tr>";
	
	$domain1="http://$domain";
	if($orderby==""){
		$orderby="links";	
	}
	
 	$query = "select * from linkanalysis_urls WHERE url LIKE '$domain1%' ORDER BY $orderby DESC,id ASC"; 

 $result=mysql_query($query);
 while ($row= mysql_fetch_array($result)) {
	 if($row[title]==""){
		 $title=$row[url];
	 }
	 else
	 {
		 $title=$row[title];
	 }
	 if($row[url]==$checked){
		$bg="#ADDFFF";	 
	 }
	 else
	 {
		$bg="#ffffff";	 
	 }
	 echo"<tr><td width=\"410\" bgcolor=\"$bg\"><a href=\"$row[url]\" title=\"$row[url]\">$title</a></td><td width=\"40\" bgcolor=\"$bg\"><a href=\"https://siteexplorer.search.yahoo.com/advsearch?p=$row[url]&bwm=i&bwmo=d&bwmf=u\" target=\"_blank\">$row[links] links</a></td>";
	 echo"<td width=\"40\"><img src=\"images/pr$row[pr].gif\" alt=\"PageRank $row[pr]\" title=\"PageRank $row[pr]\"></td>";
	 echo"<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[url]&engine=g\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/g.gif\" alt=\"Check ranking on Google\" width=\"16\" height=\"16\" title=\"Check ranking on Google\" border=\"0\"></a></td>";
	 echo"<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[url]&engine=y\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/y.gif\" alt=\"Check ranking on Yahoo\" width=\"16\" height=\"16\" title=\"Check ranking on Yahoo\" border=\"0\"></a></td>";
	 echo"<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[url]&engine=m\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/m.gif\" alt=\"Check ranking on MSN\" width=\"16\" height=\"16\" title=\"Check ranking on MSN\" border=\"0\"></a></td>";
	 echo"<td width=\"18\" bgcolor=\"$bg\"><a href=\"update.php?url=$row[url]\"><img src=\"http://www.blogstorm.co.uk/images/refresh.jpeg\" alt=\"Update link count for $row[url]\" title=\"Update link count for $row[url]\" border=\"0\"></a></td>";
	 echo"<td width=\"18\" bgcolor=\"$bg\"><a href=\"linkdata.php?url=$row[url]\"><img src=\"http://www.blogstorm.co.uk/images/drilldown.jpg\" alt=\"View link data for this url\" title=\"View link data for this url\" border=\"0\"></a></td></tr>\n";
	 
 }
 echo "</table>";
	
	
}
	    
	    
	    
	    
	    }
    else 
    {
		echo"<h2><a href=\"setup.php\"><b color=\"red\">****** Click here to create your database tables ******</b></a></h2><BR><BR>";
	} 
	
	
	include("footer.php");
?>
