<html>
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
	<h2>Current integer set: [<span id="int-set"></span>]</h2>

	<script type="text/javascript">
        	var es = new EventSource("<?php echo action('MainController@updateRandom'); ?>");
        	es.onmessage = function(event)
		{
			$('#int-set').html(event.data);
        	};
	</script>
    </body>
</html>
