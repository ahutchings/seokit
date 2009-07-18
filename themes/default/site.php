<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">

                    <h2>Results for <?php echo $this->domain ?></h2>
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
                        <?php foreach ($this->site_pages as $row): ?>
                            <tr>
                                <td><a href="/site/page?url=<?php echo urlencode($row['url']) ?>" title="View link data for this url"><?php echo (empty($row['title'])) ? $row['url'] : $row['title']; ?></a></td>
                                <td class="text-right"><a href="https://siteexplorer.search.yahoo.com/advsearch?p=<?php echo $row['url'] ?>&bwm=i&bwmo=d&bwmf=u" target="_blank"><?php echo $row['links'] ?></a></td>
                                <td class="text-center"><img src="<?php echo Options::get('theme_path') ?>images/pr<?php echo $row['pr'] ?>.gif" alt="PageRank <?php echo $row['pr'] ?>" title="PageRank <?php echo $row['pr'] ?>"></td>
                                <td class="text-center">
                                    <a href="http://www.google.com/search?hl=en&amp;q=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Google">G</a>&nbsp;
                                    <a href="http://search.yahoo.com/search?p=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Yahoo">Y!</a>&nbsp;
                                    <a href="http://www.bing.com/search?q=<?php echo $row['title'] ?>" target="_blank" title="Check ranking on Bing">B</a>
                                </td>
                                <td><a href="update.php?url=<?php echo $row['url'] ?>"><img src="<?php echo Options::get('theme_path') ?>images/arrow_refresh.png" alt="Update link count for <?php echo $row['url'] ?>"></a></td>
                                <td><a href="<?php echo $row['url'] ?>" title="Visit this URL"><img src="<?php echo Options::get('theme_path') ?>images/magnifier.png" alt="Visit this url" ></a></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

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
