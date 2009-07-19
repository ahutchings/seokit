<?php include 'header.php' ?>
        <div id="bd">
           	<div id="yui-main">
            	<div class="yui-b"><div class="yui-g">
                    <h2>Are you sure?</h2>

                    <p>Are you sure you want to delete <?php echo $this->site->domain ?> and all of its pages?</p>

                    <p><a href="/site/delete?id=<?php echo $this->site->id ?>&amp;confirm=yes">Yes</a> <a href="/">No</a></p>
                </div></div>
            </div>
<?php include 'footer.php' ?>
