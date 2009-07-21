<?php include 'header.php' ?>

<div id="bd">
    <div id="yui-main">
        <div class="yui-a"><div class="yui-g">

            <div class="block">
                <div class="bd">
                    <h2>Add a keyword for <?php echo $this->site->domain ?></h2>

                    <form action="/site/keyword/add/" id="add-keyword" method="post">
                    	<label>Keyword</label>
                    	<input type="hidden" name="id" id="id" value="<?php echo $this->site->id ?>">
                    	<input name="keyword" id="keyword" size="45" type="text" class="text">
                    	<input value="Submit" type="submit">
                    </form>
                </div>
            </div>

        </div></div>
    </div>
</div>

<?php include 'footer.php' ?>
