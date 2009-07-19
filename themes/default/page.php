<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">
                    <h2>Links to <?php echo $this->url ?></h2>

                    <p><a class="button" href="/site/page/update?url=<?php echo urlencode($this->url) ?>">Refresh All</a></p>

                    <table>
                        <thead>
                            <tr>
                            	<th>Page Title</th>
                            	<th class="text-right">Incoming Links</th>
                            	<th class="text-center">PageRank</th>
                            	<th class="text-center">Title Search</th>
                            	<th></th>
                        	</tr>
                    	</thead>
                    	<tbody>
                    	<?php foreach ($this->incoming_links as $inlink): ?>
                            <tr>
                                <td><a href="<?php echo $inlink->url ?>" title="Visit <?php echo $inlink->url ?>"><?php echo $inlink->title ?></a></td>
                                <td class="text-right"><a href="https://siteexplorer.search.yahoo.com/advsearch?p=<?php echo $inlink->url ?>&amp;bwm=i&amp;bwmo=d&amp;bwmf=u" target="_blank" title="View incoming links using Yahoo! Site Explorer"><?php echo $inlink->inlink_count ?></a></td>
                                <td class="text-center"><img src="<?php echo Options::get('theme_path') ?>images/pr<?php echo $inlink->pagerank ?>.gif" alt="PageRank <?php echo $inlink->pagerank ?>"></td>
                                <td class="text-center">
                                    <a href="http://www.google.com/search?hl=en&amp;q=<?php echo $inlink->title ?>" target="_blank" title="Search for this title on Google">G</a>&nbsp;
                                    <a href="http://search.yahoo.com/search?p=<?php echo $inlink->title ?>" target="_blank" title="Search for this title on Yahoo!">Y!</a>&nbsp;
                                    <a href="http://www.bing.com/search?q=<?php echo $inlink->title ?>" target="_blank" title="Search for this title on Bing">B</a>
                                </td>
                                <td><a href="/site/page/inlink/update?linking_page=<?php echo urlencode($inlink->url) ?>&amp;url=<?php echo urlencode($this->url) ?>" title="Update incoming link count for <?php echo $inlink->url ?>"><img src="<?php echo Options::get('theme_path') ?>images/arrow_refresh.png" alt="Update incoming link count for <?php echo $inlink->url ?>"></a></td>
                            </tr>
                	    <?php endforeach ?>
                	    </tbody>
                    </table>
                </div></div>
            </div>
<?php include 'footer.php' ?>
