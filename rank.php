<?php

$url    = $_GET["url"];
$engine = $_GET["engine"];
$url    = mysql_escape_string($url);
$engine = mysql_escape_string($engine);


$fp = fopen($url, 'r');
while (! feof($fp)){
    $content .= fgets ($fp, 1024);
    if (stristr($content, '<title>' )){
        break;
    }
}
if (eregi("<title>(.*)</title>", $content, $out)) {
    $str   = explode(' ', $out[1]);
    $query = "$str[0] $str[1] $str[2] $str[3] $str[4] $str[5] $str[6]";

    if ($engine == "g"){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://www.google.com/search?hl=en&q=$query");
        exit();
    } elseif ($engine == "y"){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://search.yahoo.com/search?p=$query");
        exit();

    } elseif ($engine == "m"){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://search.msn.com/results.aspx?q=$query");
        exit();
    }
} else{
    include 'header.php';
    echo "Error, could not find the page title.";
    include 'footer.php';
}



?>