<?php

include 'config.inc.php';
include 'header.php';

if (!isset($_GET['domain'])) {
    $q = "SELECT * FROM domains ORDER BY id ASC";
    $domains = $db->query($q)->fetchAll();
    ?>
    <h2>Your domains</h2>
    <table>
    <?php foreach ($domains as $domain): ?>
        <tr>
            <td width="50"><?php echo $domain['id'] ?></td>
            <td width="200"><a href="index.php?domain=<?php echo $domain['domain'] ?>"><?php echo $domain['domain'] ?></a></td>
            <td><img src="images/pr<?php echo $domain['pr'] ?>.gif" alt="PageRank <?php echo $domain['pr'] ?>" title="PageRank <?php echo $domain['pr'] ?>"></td>
            <td width="30"><a href="update.php?domain=<?php echo $domain['domain'] ?>"><img src="http://www.blogstorm.co.uk/images/refresh.jpeg" alt="Update all link counts for <?php echo $domain['domain'] ?>" title="Update all link counts for <?php echo $domain['domain'] ?>" border="0"></a></td>
            <td><a href="delete.php?domain=<?php echo $domain['domain'] ?>">Delete entire domain</a></td>
        </tr>
    <?php endforeach; ?>

    </table><br><a href="addsite.php">Add a new domain</a>

    <?php

} else {

    $orderby = $_GET["orderby"];
    $checked = $_GET["checked"];
    $domain  = mysql_escape_string($_GET["domain"]);

    echo "<h2>Results for $domain</h2>";
    echo "<table>";
    echo "<tr><td></td><td><a href=\"index.php?domain=$domain&orderby=links\">Links</a></td><td><a href=\"index.php?domain=$domain&orderby=pr\">PR</a></td><td colspan=\"5\"></td></tr>";

    $domain1 = "http://$domain";

    if (empty($orderby)){
        $orderby = "links";
    }

    $q = "SELECT * FROM urls WHERE url LIKE '$domain1%' ORDER BY $orderby DESC, id ASC";

    while ($row = $db->query($q)->fetch()) {

        $title = (empty($row[title])) ? $row['url'] : $row['title'];
        $bg    = ($row['url'] == $checked) ? '#ADDFFF' : '#FFF';

        echo "<tr><td width=\"410\" bgcolor=\"$bg\"><a href=\"$row[url]\" title=\"$row[url]\">$title</a></td><td width=\"40\" bgcolor=\"$bg\"><a href=\"https://siteexplorer.search.yahoo.com/advsearch?p=$row[url]&bwm=i&bwmo=d&bwmf=u\" target=\"_blank\">$row[links] links</a></td>";
        echo "<td width=\"40\"><img src=\"images/pr$row[pr].gif\" alt=\"PageRank $row[pr]\" title=\"PageRank $row[pr]\"></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[url]&engine=g\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/g.gif\" alt=\"Check ranking on Google\" width=\"16\" height=\"16\" title=\"Check ranking on Google\" border=\"0\"></a></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[url]&engine=y\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/y.gif\" alt=\"Check ranking on Yahoo\" width=\"16\" height=\"16\" title=\"Check ranking on Yahoo\" border=\"0\"></a></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"rank.php?url=$row[url]&engine=m\" target=\"_blank\"><img src=\"http://www.blogstorm.co.uk/images/m.gif\" alt=\"Check ranking on MSN\" width=\"16\" height=\"16\" title=\"Check ranking on MSN\" border=\"0\"></a></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"update.php?url=$row[url]\"><img src=\"http://www.blogstorm.co.uk/images/refresh.jpeg\" alt=\"Update link count for $row[url]\" title=\"Update link count for $row[url]\" border=\"0\"></a></td>";
        echo "<td width=\"18\" bgcolor=\"$bg\"><a href=\"linkdata.php?url=$row[url]\"><img src=\"http://www.blogstorm.co.uk/images/drilldown.jpg\" alt=\"View link data for this url\" title=\"View link data for this url\" border=\"0\"></a></td></tr>\n";

    }

    echo "</table>";
}

include 'footer.php';
