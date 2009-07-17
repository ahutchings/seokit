<?php include 'header.php' ?>
    <h2>Are you sure?</h2>
    <p>Are you sure you want to delete all the urls for <?php echo $this->site->domain ?>?<br>
    <a href="/site/delete?id=<?php echo $this->site->id ?>&amp;confirm=yes">Yes</a> <a href="/">No</a></p>
<?php include 'footer.php' ?>
