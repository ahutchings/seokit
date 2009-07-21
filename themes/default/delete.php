<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">
                    <h2>Are you sure?</h2>

                    <p>Are you sure you want to delete <b><?php echo $this->site->domain ?></b> and all of its pages?</p>

                    <p>
                    	<a class="button" href="/site/delete?id=<?php echo $this->site->id ?>&amp;confirm=yes">Delete Site</a>
                    	<a class="button" href="/">Cancel</a>
                	</p>
                </div></div>
            </div>
<?php include 'footer.php' ?>
