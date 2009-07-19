<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-a"><div class="yui-g">

                    <h2>Results for <?php echo $this->site->domain ?></h2>
                    <table>
                        <thead>
                            <tr>
                            	<th>Page Title</th>
                            	<th class="text-right">Incoming Links</th>
                            	<th class="text-center">PageRank</th>
                            	<th class="text-center">Title Search</th>
                            	<th colspan="2"></th>
                        	</tr>
                    	</thead>
                    	<tbody>
                        <?php foreach ($this->site->pages as $row): ?>
                            <tr>
                                <td><a href="/site/page?url=<?php echo urlencode($row['url']) ?>" title="View incoming links to this URL"><?php echo (empty($row['title'])) ? $row['url'] : $row['title']; ?></a></td>
                                <td class="text-right"><a href="https://siteexplorer.search.yahoo.com/advsearch?p=<?php echo $row['url'] ?>&bwm=i&bwmo=d&bwmf=u" target="_blank" title="View incoming links using Yahoo! Site Explorer"><?php echo $row['links'] ?></a></td>
                                <td class="text-center"><img src="<?php echo Options::get('theme_path') ?>images/pr<?php echo $row['pr'] ?>.gif" alt="PageRank <?php echo $row['pr'] ?>" title="PageRank <?php echo $row['pr'] ?>"></td>
                                <td class="text-center">
                                    <a href="http://www.google.com/search?hl=en&amp;q=<?php echo $row['title'] ?>" target="_blank" title="Search for this title on Google">G</a>&nbsp;
                                    <a href="http://search.yahoo.com/search?p=<?php echo $row['title'] ?>" target="_blank" title="Search for this title on Yahoo">Y!</a>&nbsp;
                                    <a href="http://www.bing.com/search?q=<?php echo $row['title'] ?>" target="_blank" title="Search for this title on Bing">B</a>
                                </td>
                                <td><a href="/site/page/update?url=<?php echo urlencode($row['url']) ?>" title="Update incoming link count for <?php echo $row['url'] ?>"><img src="<?php echo Options::get('theme_path') ?>images/arrow_refresh.png" alt="Update link count for <?php echo $row['url'] ?>"></a></td>
                                <td><a href="<?php echo $row['url'] ?>" title="Visit <?php echo $row['url'] ?>"><img src="<?php echo Options::get('theme_path') ?>images/magnifier.png" alt="Visit this url" ></a></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                </div></div>
            </div>

<?php include 'footer.php' ?>
