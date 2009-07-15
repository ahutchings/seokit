<?php

	include('config.inc.php');
	
	
	$url=$_GET["url"];
	$url=mysql_escape_string($url);

	include("header.php");

if($url==""){
	?>
<h2>Add a site to be analysed</h2>
<p>To input a site just enter the url in the box below and the script will store all the known url's in the database ready for analysis.

<form action="addsite.php" method="get">
Site URL: <input name="url" size="45" value="http://www.site.com" type="text" /><input value="Submit" style="width: 50px" type="submit" />
</form>
<p>The script captures data using the "Pages in Site" feature of <a href="https://siteexplorer.search.yahoo.com">Yahoo Site Explorer</a>.
<p>You can add a site that's already in the database if you need to for any reason, we won't store duplicate urls.
<?php
}
else
{
?>
<h2>Spider results</h2>
<?php
	$str = explode('/',$url);
    $domain="$str[2]";
	$result = MYSQL_QUERY("SELECT domain FROM linkanalysis_domains WHERE domain='$domain' LIMIT 1");
	
	if (!$row=mysql_fetch_array($result)){
			$pr="$scriptlocation/getpr.php?url=$domain";		
			$pr=@file_get_contents($pr);
	if (mysql_query("INSERT INTO linkanalysis_domains VALUES('','$domain','$pr')") or die(mysql_error())){
        
        }
    
	}
echo "$url being spidered.........<BR>\n";


$counter = 0;
$type = 0;
$tag = "";
$itemInfo = array();
$channelInfo = array();

function opening_element($xmlParser, $name, $attribute){

global $tag, $type;

$tag = $name;

if($name == "RESULTSET"){
$type = 1;
}
else if($name == "RESULT"){
$type = 2;
}

}//end opening element

function closing_element($xmlParser, $name){

global $tag, $type, $counter;

$tag = "";
if($name == "RESULT"){
$type = 0;
$counter++;
}
else if($name == "RESULTSET"){
$type = 0;
}
}//end closing_element

function c_data($xmlParser, $data){

global $tag, $type, $channelInfo, $itemInfo, $counter;

$data = trim(htmlspecialchars($data));

if($tag == "TITLE" || $tag == "DESCRIPTION" || $tag == "URL" || $tag == "PUBDATE"){
if($type == 1){

$channelInfo[strtolower($tag)] = $data;

}//end checking channel
else if($type == 2){

$itemInfo[$counter][strtolower($tag)] .= $data;

}//end checking for item
}//end checking tag
}//end cdata funct

$start="1";
while ( $start <= 1000 ) {
$request="";
$request = 'http://search.yahooapis.com/SiteExplorerService/V1/pageData?appid=FUH9aZjV34GSWglIPsIhtcRNWA3_oTLSJDq51iBY8_P7.ykFyeZOcLoH.Hz4AiI-&query=';
$request.=$url;
$request.='&results=100&start=';
$request.=$start;

//echo "$request <BR>";


$xmlParser = xml_parser_create();

xml_parser_set_option($xmlParser, XML_OPTION_CASE_FOLDING, TRUE);
xml_parser_set_option($xmlParser, XML_OPTION_SKIP_WHITE, TRUE);

xml_set_element_handler($xmlParser, "opening_element", "closing_element");
xml_set_character_data_handler($xmlParser, "c_data");

$fp="";
$fp = @file($request);

if(!$fp){
echo"Cannot connect to Yahoo, you might have used more than 5000 queries today?";
include("footer.php");
exit();	
}
foreach($fp as $line){
if(!xml_parse($xmlParser, $line)){
echo"Cannot parse xml file";
include("footer.php");
exit();
}
}

foreach($itemInfo as $items){
	$url=$items['url'];
	$title=$items['title'];
	$title=mysql_escape_string($title);
	
	$result = MYSQL_QUERY("SELECT url FROM linkanalysis_urls WHERE url='$url' LIMIT 1");
	
	if (!$row=mysql_fetch_array($result)){
			$pr="$scriptlocation/getpr.php?url=$url";		
		$pr=@file_get_contents($pr);
	if (mysql_query("INSERT INTO linkanalysis_urls VALUES('','$url','$title','0','','$pr')") or die(mysql_error())){
        echo "<a href=\"$url\">$url</a> was added!<br />\n";
        $update="$scriptlocation/getlinks.php?url=$url";
	$update=file_get_contents($update);
        }
	
	}
	else
	{	
		echo "<a href=\"$url\">$url</a> is already listed <BR>\n";
		$update="$scriptlocation/getlinks.php?url=$url";
	$update=file_get_contents($update);
	}
	

}

$start=$start+100;
}
	

}

include("footer.php");
?>