<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">
                    <h2>Spider an XML sitemap for Pages</h2>

                    <p>If you would like to input a lot of pages it is sometimes better to
                    use an XML sitemap rather than relying on Yahoo! to return indexed pages.</p>

                    <p><b>Note:</b> if you spider a site using this method the page titles
                    will not be added to the database like they will be if you use <a
                    	href="/site/create">this method</a>.</p>

                    <form action="/site/from-sitemap" id="sitemap" method="post">
                    	<label for="url">Sitemap URL</label>
                    	<input name="url" size="45" id="url" type="text" class="text" />
                    	<input value="Submit" type="submit" />
                    </form>
                </div></div>
            </div>
<?php include 'footer.php' ?>
