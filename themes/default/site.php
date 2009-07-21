<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-a"><div class="yui-g">

					<h2><?php echo $this->site->domain ?></h2>

                	<div class="block" id="keywords">
                		<div class="hd">
                            <h3>Keywords</h3>
                		</div>
                		<div class="bd">
                			<table>
                				<thead>
									<tr>
										<th>Keyword</th>
									</tr>
                				</thead>
                				<tbody>
                				<?php foreach ($this->site->keywords as $keyword): ?>
                					<tr>
                						<td><?php echo $keyword->text ?></td>
                					</tr>
                				<?php endforeach ?>
                				</tbody>
                			</table>

                			<p><a href="/site/keyword/add/?id=<?php echo $this->site->id ?>" class="button">Add a keyword</a></p>
                		</div>
            		</div>

                	<div class="block" id="pages">
                		<div class="hd">
                            <h3>Pages</h3>
                		</div>
                		<div class="bd">
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
                                <?php foreach ($this->site->pages as $page): ?>
                                    <tr>
                                        <td><a href="/site/page?url=<?php echo urlencode($page->url) ?>" title="View incoming links to this URL"><?php echo (empty($page->title)) ? $page->url : $page->title; ?></a></td>
                                        <td class="text-right"><a href="https://siteexplorer.search.yahoo.com/advsearch?p=<?php echo $page->url ?>&bwm=i&bwmo=d&bwmf=u" target="_blank" title="View incoming links using Yahoo! Site Explorer"><?php echo $page->inlink_count ?></a></td>
                                        <td class="text-center"><img src="<?php echo Options::get('theme_path') ?>images/pr<?php echo $page->pagerank ?>.gif" alt="PageRank <?php echo $page->pagerank ?>"></td>
                                        <td class="text-center">
                                            <a href="http://www.google.com/search?hl=en&amp;q=<?php echo $page->title ?>" target="_blank" title="Search for this title on Google">G</a>&nbsp;
                                            <a href="http://search.yahoo.com/search?p=<?php echo $page->title ?>" target="_blank" title="Search for this title on Yahoo">Y!</a>&nbsp;
                                            <a href="http://www.bing.com/search?q=<?php echo $page->title ?>" target="_blank" title="Search for this title on Bing">B</a>
                                        </td>
                                        <td><a href="/site/page/update?url=<?php echo urlencode($page->url) ?>" title="Update incoming link count for <?php echo $page->url ?>"><img src="<?php echo Options::get('theme_path') ?>images/arrow_refresh.png" alt="Update link count for <?php echo $page->url ?>"></a></td>
                                        <td><a href="<?php echo $page->url ?>" title="Visit <?php echo $page->url ?>"><img src="<?php echo Options::get('theme_path') ?>images/magnifier.png" alt="Visit this url" ></a></td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
						</div>
					</div>

                </div></div>
            </div>

<?php include 'footer.php' ?>
