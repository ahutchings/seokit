<?php include 'header.php' ?>
        <div id="bd">
            <div id="yui-main">
                <div class="yui-a"><div class="yui-g">

                    <div class="block">
                        <div class="bd">
                            <h2>Log Entries</h2>
                            <table id="logs">
                                <thead>
                                    <tr>
                                        <td><input type="checkbox" name="test10" id="test10" value=""></td>
                                        <td>Date &amp; Time</td>
                                        <td><a href="#">Severity</a></td>
                                        <td><a href="#">Message</a></td>
                                        <td><?php echo $this->pager ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php foreach ($this->logs as $log): ?>
                                    <tr>
                                        <td><input type="checkbox" name="test14" id="test14" value=""></td>
                                        <td><?php echo $log->created_at ?></td>
                                        <td><?php echo $log->level_friendly ?></td>
                                        <td><?php echo $log->message ?></td>
                                        <td class="text-right"><a href="#">view</a> | <a href="#">delete</a></td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>

							<?php echo $this->pager ?>
                        </div>
                    </div>

                </div></div>
            </div>
        </div>
<?php include 'footer.php' ?>
