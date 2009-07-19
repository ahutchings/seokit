	<div id="ft"></div>

	</div>
<script type="text/javascript">
	$(document).ready(function () {
	    $('tbody tr:odd').addClass('odd');

		$('#add-site #url').clearingInput({text: 'http://www.example.com'});
		$('#add-url #url').clearingInput({text: 'http://www.example.com/single-page'});
		$('#sitemap #url').clearingInput({text: 'http://www.example.com/sitemap.xml'});
	});
</script>
</body>
</html>
