<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">
                    <h2>Links to <?php echo $this->url ?></h2>

                    <p><a href="/site/page?url=<?php echo urlencode($this->url) ?>&amp;refresh=yes">Refresh this data using Yahoo</a></p>

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
                    	<?php foreach ($this->incoming_links as $row): ?>
                            <tr>
                                <td><a href="<?php echo $row['linking_page'] ?>" title="<?php echo $row['linking_page'] ?>"><?php echo $row['linking_page_title'] ?></a></td>
                                <td class="text-right"><a href="https://siteexplorer.search.yahoo.com/advsearch?p=<?php echo $row['linking_page'] ?>&amp;bwm=i&amp;bwmo=d&amp;bwmf=u" target="_blank"><?php echo $row['linking_page_inlinks'] ?></a></td>
                                <td class="text-center"><img src="<?php echo Options::get('theme_path') ?>images/pr<?php echo $row['linking_page_pr'] ?>.gif" alt="PageRank <?php echo $row['linking_page_pr'] ?>"></td>
                                <td class="text-center">
                                    <a href="http://www.google.com/search?hl=en&amp;q=<?php echo $row['linking_page_title'] ?>" target="_blank" title="Check ranking on Google">G</a>&nbsp;
                                    <a href="http://search.yahoo.com/search?p=<?php echo $row['linking_page_title'] ?>" target="_blank" title="Check ranking on Yahoo!">Y!</a>&nbsp;
                                    <a href="http://www.bing.com/search?q=<?php echo $row['linking_page_title'] ?>" target="_blank" title="Check ranking on Bing">B</a>
                                </td>
                                <td><a href="/site/page/update?linking_page=<?php echo $row['linking_page'] ?>&amp;url=<?php echo $this->url ?>"><img src="<?php echo Options::get('theme_path') ?>images/arrow_refresh.png" alt="Update link count for <?php echo $row['linking_page'] ?>"></a></td>
                            </tr>
                	    <?php endforeach ?>
                	    </tbody>
                    </table>
                </div></div>
            </div>
<?php include 'footer.php' ?>
