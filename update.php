<?php
include('config.inc.php');
//include("header.php");

$url=$_GET["url"];
$linking_page=$_GET["linking_page"];
$linking_page=mysql_escape_string($linking_page);

$domain=$_GET["domain"];
$url=mysql_escape_string($url);
$domain=mysql_escape_string($domain);


$today= date("Y-m-d");
if($linking_page!=""){
    $request = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=';
    $request.=$yahoo_api_key;
    $request.='&query=';
    $request.=$linking_page;
    $request.='&output=php';
    $request.='&omit_inlinks=domain';
    $output = unserialize(file_get_contents($request));
    $incoming_links=$output[ResultSet][totalResultsAvailable];

    if (ctype_digit($incoming_links)) {
        $pr="$scriptlocation/getpr.php?url=$linking_page";
        $pr=@file_get_contents($pr);
        $result3 = MYSQL_QUERY("UPDATE linkanalysis_linkdata SET linking_page_inlinks='$incoming_links',linking_page_pr='$pr' WHERE linking_page='$linking_page' LIMIT 1");
        //echo"$incoming_links links to $url";
    }

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: linkdata.php?url=$url");
    exit();


     
     
}
else
{


    if($url!=""){
        $request = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=';
        $request.=$yahoo_api_key;
        $request.='&query=';
        $request.=$url;
        $request.='&output=php';
        $request.='&omit_inlinks=domain';
        $output = unserialize(file_get_contents($request));
        $incoming_links=$output[ResultSet][totalResultsAvailable];

        if (ctype_digit($incoming_links)) {
            $pr="$scriptlocation/getpr.php?url=$url";
            $pr=@file_get_contents($pr);
            $result3 = MYSQL_QUERY("UPDATE linkanalysis_urls SET checkdate='$today',links='$incoming_links',pr='$pr' WHERE url='$url' LIMIT 1");
            //echo"$incoming_links links to $url";
        }
        $str = explode('/',$url);
        $domain="$str[2]";

        header("HTTP/1.1 301 Moved Permanently");
        header("Location: index.php?domain=$domain&checked=$url");
        exit();

    }

    if(($domain!="")&&($url=="")){
        include("header.php");
        echo"<h2>Getting link data</h2><p>This might take a while but you will be <a href=\"index.php?domain=$domain\">sent here when it finishes</a>.";

        $domain1="http://$domain";
        $counter="0";
        while ( $counter <= 5000 ) {
            $result = MYSQL_QUERY("SELECT * FROM linkanalysis_urls WHERE checkdate !='$today' AND url LIKE '$domain1%' ORDER BY id DESC LIMIT 1");

            if (!$row=mysql_fetch_array($result)){
                echo"Every url has been checked already today";
                exit();
            }
            $url=$row[url];

            $request = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=';
            $request.=$yahoo_api_key;
            $request.='&query=';
            $request.=$url;
            $request.='&output=php';
            $request.='&omit_inlinks=domain';
            $output = @unserialize(file_get_contents($request));
            $incoming_links=$output[ResultSet][totalResultsAvailable];

            if (ctype_digit($incoming_links)) {
                $pr="$scriptlocation/getpr.php?url=$url";
                $pr=@file_get_contents($pr);
                $result4 = MYSQL_QUERY("UPDATE linkanalysis_urls SET checkdate='$today',links='$incoming_links',pr='$pr' WHERE url='$url' LIMIT 1");
                $result5 = MYSQL_QUERY("UPDATE linkanalysis_domains SET pr='$pr' WHERE domain='$domain' LIMIT 1");
                echo"$incoming_links links to $url <BR> \n";
            }
            else
            {
                echo"Error getting link count for $url <BR> \n";
            }

            $counter = $counter + 1;
        }
    }
    echo"<meta http-equiv=\"refresh\" content=\"10;url=index.php?domain=$domain\">";
    include("footer.php");
}

//
?>