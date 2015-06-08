
<script>
	(function($) {
		setTimeout(function() {
			$('#errorMessage').fadeOut();
		}, 1500);
		var specificFunnel = 'https://api.clickfunnels.com/funnels.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';
		$.getJSON(specificFunnel, function(data) {
		   $('.checkSuccess').html('<i class="fa fa-check successGreen"></i>');
		   $('.checkSuccessDev').html('<i class="fa fa-check"> Connected</i>');

		  }).fail(function(jqXHR) {
	     		$('.checkSuccess').html('<i class="fa fa-times errorRed"></i>');
	     		$('.checkSuccessDev').html('<i class="fa fa-times"> Not Connected</i>');
	     		$('.badAPI').show();
		  });
		  $('#showDataTesting').click(function() {
		  	$('#devShowTesting').toggle();
		  });
	})(jQuery);
</script>
<style>
	#checkPHPStuff {
		padding: 10px 15px;
		background: #272822;
		background-image: url(<?php echo plugins_url( 'geobg.png', __FILE__ ); ?>);
	   	 border-bottom: 3px solid rgba(0,0,0,0.25);
		color: #D3D3C9;
		border-radius: 5px;
		margin:30px 10px;
		float: left;
		 margin-left: 195px;
		width: 340px;
	}
	#checkPHPStuff span {
		display: block;
		line-height: 1.6em;
		padding: 4px 0;
		border-bottom: 1px solid rgba(255, 255, 255, .3);
	}
	#checkPHPStuff span strong {
		float: right;
	}
	#checkPHPStuff span strong i.fa-check {
		font-weight: bold;
		color: #8FC742;
	}
	#checkPHPStuff span strong i.fa-times {
		font-weight: bold;
		color: #E34E3D;
	}
	#checkPHPStuff span:last-child {
		border-bottom: none;
	}



	#checkJSONStuff h3, #checkPHPStuff h3 {
		color: #D3D3C9;
		margin: 10px 0;
		font-weight: 200;
		opacity: .5;
	}
	
	#adminTesting h2 {
		text-align: center;
		margin: 10px 0;
		margin-bottom: 0;
		width: 760px;
	}
</style>
<div id="devShowTesting" style="display: none">
	<br clear="both"><br>
	<div id="adminTesting">
		<h2><i class="fa fa-dashboard"></i> Developer Testing Check PHP and JSON</h2>
	</div>
	<div id="checkPHPStuff" >
		<h3>Test PHP Server Details</h3>
		<span>CURL:  <strong><?php echo function_exists('curl_version') ? '<i class="fa fa-check"> Enabled</i>' : '<i class="fa fa-times">Disabled</i>'  ?></strong></span>
		<span>File Get Contents:  <strong><?php echo file_get_contents(__FILE__) ? '<i class="fa fa-check"> Enabled</i>' : '<i class="fa fa-times">Disabled</i>' ; ?> </strong></span>
		<span>Allow URL fopen:  <strong><?php echo ini_get('allow_url_fopen') ? '<i class="fa fa-check"> Enabled</i>' : '<i class="fa fa-times">Disabled</i>' ; ?> </strong></span>
		<span>PHP Version:  <strong><?php echo PHP_VERSION; ?> </strong></span>
		<span>API Authorization Connection:  <strong class='checkSuccessDev'></strong></span>
	</div>
</div>