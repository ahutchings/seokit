<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">

                	<div class="block" id="sites">
                		<div class="hd">
                            <h2>All Sites</h2>
                		</div>
                		<div class="bd">
                            <table>
                            	<thead>
                                    <tr>
                                        <th>Site</th>
                                        <th class="text-center">PageRank</th>
                                        <th></th>
                                    </tr>
                            	</thead>
                            	<tbody>
                                <?php foreach ($this->sites as $site): ?>
                                    <tr>
                                        <td><a href="/site/?id=<?php echo $site->id ?>"><?php echo $site->domain ?></a></td>
                                        <td class="text-center"><img src="<?php echo Options::get('theme_path') ?>images/pr<?php echo $site->pr ?>.gif" alt="PageRank <?php echo $site->pr ?>" title="PageRank <?php echo $site->pr ?>"></td>
                                        <td class="text-right">
                                        	<a href="/site/update?id=<?php echo $site->id ?>" title="Update all link counts for <?php echo $site->domain ?>">refresh</a> |
                                        	<a href="/site/delete?id=<?php echo $site->id ?>" title="Delete entire site">delete</a>
                                    	</td>
                                    </tr>
                                <?php endforeach; ?>
                        		</tbody>
                            </table>
                            <p><a class="button" href="/site/create">Add a new site</a></p>
                        </div>
                	</div>

                </div></div>
            </div>

        <div id="sidebar" class="yui-b">
            <div class="block">
            	<div class="hd">
            		<h2>Competitive link analysis made easy</h2>
        		</div>
        		<div class="bd">
            		<p>This tool is designed to find out exactly which pages on
            		a website have the most links pointing to them.</p>

                    <p>In general the more links a website has the better it will rank in
                    the search engines, if you can find a page on your competitors website
                    with thousands of links then you can take inspiration from the ideas on
                    that page and get some similar links yourself.</p>

                    <p>Yahoo gives 5,000 queries per day under a single API
                    key/IP address. <a href="http://developer.yahoo.com/search/">More
                    details</a>.</p>

                    <p>Yahoo also only returns the first 1000 URLs in a site so for large
                    sites you might need to add the rest using the sitemap.xml spider.</p>
                </div>
            </div>
        </div>
<?php include 'footer.php' ?>
