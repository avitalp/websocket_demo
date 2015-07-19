<html>
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
	<!-- Don't look at me...I'm hideous!!! -->
        <h1>Hello, <?php echo $name;?></h1>
	<iframe id="player" style="display: none" width="420" height="315" src="https://www.youtube.com/embed/5xbyJGM1iUY?enablejsapi=1&rel=0" frameborder="0" allowfullscreen></iframe>
	<br>
	<h2>Current number being streamed from server is: <span id="num"></span></h2>
	<div style="font-size: 11pt">(half-duplex: SSE stream updating every <?php echo $delay; ?> seconds)</div>

	<h2>Send Avital a message:</h2>
	<div style="font-size: 11pt">(full-duplex: stream using W3C WebSocket API)</div>

	<div style="height: 250px">
		<div style="display: inline-block; width: 33%">
			<textarea id="msg" style="width: 100%; height: 100px; margin-bottom: 5px"></textarea>
			<br>
			<button id="btn-send-msg">Send</button>
		</div>
		<div style="display: inline-block; width: 33%; vertical-align: top">
			Server echo:
			<select id="server-msg" multiple="yes" style="width: 100%; background: #fefefe; color: #222"></select>
		</div>
	</div>
	<script type="text/javascript">
        	var es = new EventSource("<?php echo action('MainController@updateRandom'); ?>");
        	es.onmessage = function(event)
		{
			$('#num').html(event.data);
        	};

		var conn = new WebSocket('ws://avital.ca:8888/chat');
		conn.onmessage = function(e)
		{
			var oldMsg = $('#server-msg').text(),
			    newMsg = '<option>' + e.data + '</option>';

			$('#server-msg')
				.append(newMsg)
				.show();

			if (e.data == "burns")
			{
				var player = $('#player');
				player.show();
				player[0].src += "&autoplay=1";
			}
		};

		$('#btn-send-msg').on('click', function()
		{
			conn.send($('#msg').val());
		});
	</script>
    </body>
</html>
