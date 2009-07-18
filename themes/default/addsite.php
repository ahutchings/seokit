<?php include 'header.php' ?>

<div id="bd">
    <div id="yui-main">
        <div class="yui-a"><div class="yui-g">

            <div class="block">
                <div class="bd">
                    <h2>Add a site to be analyzed</h2>

                    <p>To input a site just enter the url in the box below and the script
                    will store all the known url's in the database ready for analysis.</p>

                    <form action="/site/create" id="add-site" method="post">
                    	<label>Site URL</label>
                    	<input name="url" id="url" size="45" type="text" class="text" />
                    	<input value="Submit" type="submit" />
                    </form>

                    <p>The script captures data using the "Pages in Site" feature of
                    <a href="https://siteexplorer.search.yahoo.com">Yahoo Site Explorer</a>.</p>

                    <p>You can add a site that's already in the database if you need to for
                    any reason, we won't store duplicate urls.</p>
                </div>
            </div>

        </div></div>
    </div>
</div>

<?php include 'footer.php' ?>
