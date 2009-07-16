<?php

include 'config.inc.php';
include 'header.php';

if (!isset($_GET['domain'])) {
    $domains = Domains::get();
    ?>
    <h2>All Domains</h2>
    <table>
    	<thead>
    		<tr>
    			<th>URL</th>
    			<th>PageRank</th>
    			<th></th>
    		</tr>
    	</thead>
    	<tbody>
        <?php foreach ($domains as $domain): ?>
            <tr>
                <td width="200"><a href="index.php?domain=<?php echo $domain->domain ?>"><?php echo $domain->domain ?></a></td>
                <td><img src="images/pr<?php echo $domain->pr ?>.gif" alt="PageRank <?php echo $domain->pr ?>" title="PageRank <?php echo $domain->pr ?>"></td>
                <td>
                	<a href="update.php?domain=<?php echo $domain->domain ?>" title="Update all link counts for <?php echo $domain->domain ?>">refresh</a> |
                	<a href="delete.php?id=<?php echo $domain->id ?>" title="Delete entire domain">delete</a>
            	</td>
            </tr>
        <?php endforeach; ?>
		</tbody>
    </table><br><a href="addsite.php">Add a new domain</a>

    <?php

} else {

    $orderby = $_GET["orderby"];
    $checked = $_GET["checked"];
    $domain  = mysql_escape_string($_GET["domain"]);

    ?>
    <h2>Results for <?php echo $domain ?></h2>
    <table>
    <thead>
        <tr>
        	<th>Page Title</th>
        	<th><a href="index.php?domain=<?php echo $domain ?>&orderby=links">Links</a></th>
        	<th><a href="index.php?domain=<?php echo $domain ?>&orderby=pr">PR</a></th>
        	<th colspan="5"></th>
    	</tr>
	</thead>
	<tbody>
    <?php
    $domain1 = "http://$domain";

    if (empty($orderby)) {
        $orderby = "links";
    }

    $q = "SELECT * FROM urls WHERE url LIKE '$domain1%' ORDER BY $orderby DESC, id ASC";
    $urls = $db->query($q)->fetchAll();

    foreach ($urls as $row) {

        $title = (empty($row['title'])) ? $row['url'] : $row['title'];
        $bg    = ($row['url'] == $checked) ? '#ADDFFF' : '#FFF';

        ?>
        <tr style="background:<?php echo $bg ?>">
            <td width="410"><a href="linkdata.php?url=<?php echo $row['url'] ?>" title="View link data for this url"><?php echo $title ?></a></td>
            <td width="40"><a href="https://siteexplorer.search.yahoo.com/advsearch?p=<?php echo $row['url'] ?>&bwm=i&bwmo=d&bwmf=u" target="_blank"><?php echo $row['links'] ?></a></td>
            <td width="40"><img src="images/pr<?php echo $row['pr'] ?>.gif" alt="PageRank <?php echo $row['pr'] ?>" title="PageRank <?php echo $row['pr'] ?>"></td>
            <td width="18"><a href="http://www.google.com/search?hl=en&amp;q=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Google">G</a></td>
            <td width="18"><a href="http://search.yahoo.com/search?p=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Yahoo">Y!</a></td>
            <td width="18"><a href="http://www.bing.com/search?q=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Bing">B</a></td>
            <td width="18"><a href="update.php?url=<?php echo $row['url'] ?>"><img src="/images/arrow_refresh.png" alt="Update link count for <?php echo $row['url'] ?>" title="Update link count for <?php echo $row['url'] ?>" border="0"></a></td>
            <td width="18"><a href="<?php echo $row['url'] ?>" title="Visit this URL"><img src="/images/magnifier.png" alt="Visit this url" border="0"></a></td>
        </tr>
        <?php
    }

    echo "</tbody></table>";
}

include 'footer.php';
