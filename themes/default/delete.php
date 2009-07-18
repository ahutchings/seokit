<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">
                    <h2>Are you sure?</h2>

                    <p>Are you sure you want to delete all the urls for <?php echo $this->site->domain ?>?</p>

                    <p><a href="/site/delete?id=<?php echo $this->site->id ?>&amp;confirm=yes">Yes</a> <a href="/">No</a></p>
                </div></div>
            </div>
<?php include 'footer.php' ?>
