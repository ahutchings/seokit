<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">

                <?php if (!isset($_GET['domain'])): ?>

                	<div class="block" id="domains">
                		<div class="hd">
                            <h2>All Sites</h2>
                		</div>
                		<div class="bd">
                            <table>
                            	<thead>
                                    <tr>
                                        <th>URL</th>
                                        <th>PageRank</th>
                                        <th></th>
                                    </tr>
                            	</thead>
                            	<tbody>
                                <?php foreach ($this->domains as $domain): ?>
                                    <tr>
                                        <td width="200"><a href="/?domain=<?php echo $domain->domain ?>"><?php echo $domain->domain ?></a></td>
                                        <td><img src="<?php echo Options::get('theme_path') ?>images/pr<?php echo $domain->pr ?>.gif" alt="PageRank <?php echo $domain->pr ?>" title="PageRank <?php echo $domain->pr ?>"></td>
                                        <td>
                                        	<a href="update.php?domain=<?php echo $domain->domain ?>" title="Update all link counts for <?php echo $domain->domain ?>">refresh</a> |
                                        	<a href="delete.php?id=<?php echo $domain->id ?>" title="Delete entire domain">delete</a>
                                    	</td>
                                    </tr>
                                <?php endforeach; ?>
                        		</tbody>
                            </table>
                            <p><a href="addsite.php">Add a new domain</a></p>
                        </div>
                	</div>

                <?php else: ?>

                    <h2>Results for <?php echo $this->domain ?></h2>
                    <table>
                        <thead>
                            <tr>
                            	<th>Page Title</th>
                            	<th><a href="/?domain=<?php echo $this->domain ?>&orderby=links">Links</a></th>
                            	<th><a href="/?domain=<?php echo $this->domain ?>&orderby=pr">PR</a></th>
                            	<th colspan="5"></th>
                        	</tr>
                    	</thead>
                    	<tbody>
                        <?php foreach ($this->domain_pages as $row): ?>
                            <tr style="background:<?php echo ($row['url'] == $this->checked) ? '#ADDFFF' : '#FFF'; ?>">
                                <td width="410"><a href="linkdata.php?url=<?php echo $row['url'] ?>" title="View link data for this url"><?php echo (empty($row['title'])) ? $row['url'] : $row['title']; ?></a></td>
                                <td width="40"><a href="https://siteexplorer.search.yahoo.com/advsearch?p=<?php echo $row['url'] ?>&bwm=i&bwmo=d&bwmf=u" target="_blank"><?php echo $row['links'] ?></a></td>
                                <td width="40"><img src="images/pr<?php echo $row['pr'] ?>.gif" alt="PageRank <?php echo $row['pr'] ?>" title="PageRank <?php echo $row['pr'] ?>"></td>
                                <td width="18"><a href="http://www.google.com/search?hl=en&amp;q=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Google">G</a></td>
                                <td width="18"><a href="http://search.yahoo.com/search?p=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Yahoo">Y!</a></td>
                                <td width="18"><a href="http://www.bing.com/search?q=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Bing">B</a></td>
                                <td width="18"><a href="update.php?url=<?php echo $row['url'] ?>"><img src="<?php echo Options::get('theme_path') ?>/images/arrow_refresh.png" alt="Update link count for <?php echo $row['url'] ?>" title="Update link count for <?php echo $row['url'] ?>" border="0"></a></td>
                                <td width="18"><a href="<?php echo $row['url'] ?>" title="Visit this URL"><img src="<?php echo Options::get('theme_path') ?>/images/magnifier.png" alt="Visit this url" border="0"></a></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                <?php endif ?>
                </div></div>
            </div>

        <div id="sidebar" class="yui-b">
            <div class="block">
            	<div class="hd">
            		<h2>Competitive link analysis made easy</h2>
        		</div>
        		<div class="bd">
            		<p><img src="http://www.blogstorm.co.uk/images/tools-thumb.gif"
                	alt="Link tool" align="left"> This tool is designed to find out exactly
                    which pages on a website have the most links pointing to them.</p>

                    <p>In general the more links a website has the better it will rank in
                    the search engines, if you can find a page on your competitors website
                    with thousands of links then you can take inspiration from the ideas on
                    that page and get some similar links yourself.</p>

                    <p>Yahoo gives 5,000 queries per day under a single API key/IP address
                    which should be plenty. <a href="http://developer.yahoo.com/search/">More
                    details</a>.</p>

                    <p>Yahoo also only returns the first 1000 urls in a site so for large
                    sites you might need to add the rest using the sitemap.xml spider.</p>
                </div>
            </div>
        </div>
<?php include 'footer.php' ?>
