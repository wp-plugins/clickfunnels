<style>
	.api {
	width: 780px;
	margin-top: 20px;
	border-radius: 5px;
	}
		.api form {
			padding:  0;
		}
		.api form h3 {
			padding: 0;
			padding-bottom: 10px;
			margin: 0;
			font-size: 17px;
			margin-top: 10px;
			color: #39464E;
			font-weight: 400;
		}
		.api form input {
			width: 100%;
			font-size: 15px;
			padding: 7px;
			margin-bottom: 17px;
		}
	.apiHeader {
		background: #39464E url(<?php echo plugins_url( 'geobg.png', __FILE__ ); ?>) repeat;
		border-bottom: 3px solid rgba(0,0,0,0.25);
		padding: 10px;
		padding-top: 15px;
		border-top-right-radius: 5px;
		border-top-left-radius: 5px;
	}
		.apiHeader img {
			width: 200px;
			float: left;
			margin-left: 10px;
		}
		.apiHeader a {
			float: right;
			display: block;
			margin-top: 2px !important;
			margin-right: 1px !important;
		}
	.apiSubHeader {
	    background-color: #0166AE;
	    background-image: url(<?php echo plugins_url( 'geobg.png', __FILE__ ); ?>);
	    border-bottom: 3px solid rgba(0,0,0,0.25);
	    color: #fff;
	    padding: 20px;
	}
	.apiSubHeader h2 {
	    margin: 0 !important;
	    padding: 0 !important;
	    color: #fff;
	    font-weight: bold;
	    font-size: 17px;
	    text-shadow: 1px 1px 0 #0367AE;
	}
	.leftSide {
		width: 380px;
		float: left;
	}
		.leftSide h3 {
			font-weight: 900;
			font-size: 13px !important;
		}
	.rightSide {
		width: 280px;
		float: left;
		padding: 20px;
		opacity: .7;
		padding-left: 30px;
	}
	.rightSide p {
		font-size: 12px;
		line-height: 18px;
	}
	#errorMessage {
		clear: both;
		padding: 8px;
		background: #fafafa;
		border: 2px solid #ddd;
	}
		#errorMessage.success {
			border: 2px solid #49BB85;
			background: #49BB85;
			color: #fff;
		}
		#errorMessage.error {
			border: 2px solid #B72D1C;
			background: #B72D1C;
			color: #fff;
		}
	.successGreen {
		color: #49BB85;
	}
	.errorRed {
		color: #B72D1C;
	}
	#errorMessage.badAPI {
		border: 2px solid #B72D1C;
		background: #B72D1C;
		color: #fff;
		display: none;
	}
	.copyrightInfo {
	    width: 780px;
	    padding: 0 10px;
	    float: left;
	    color: #999;
	    font-size: 11px;
	}
	.copyrightInfo p {
	    font-size: 11px;
	}
	.apiHeader a {
        float: right;
        display: block;
        color: #fff;
        font-size: 13px;
        text-decoration: none;
        margin-right: 4px !important;
        background-color: rgba(0, 0, 0, .3);
        border: 2px solid #2b2e30;
        color: #afbbc8 !important;
        font-weight: 700;
        margin-top: 0px !important;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        padding: 7px;
        padding-left: 12px;
        padding-right: 12px;
        text-transform: uppercase;
        text-decoration: none;
        font-size: 14px;
    }
    .apiHeader a:hover {
        border: 2px solid #1D81C8;
        background-color: rgba(0, 0, 0, .6);
        color: #fff !important;
    }
    #side-sortables {
        display: none !important;
    }

    #apiFooter {
        background: #F1F1F1;
    
        padding: 10px 14px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    #apiFooter a:hover {
        text-decoration: none;
    }
    #apiSettings {
    	padding: 10px 25px;
    	padding-top: 20px;
    	border: 10px solid #F1F1F1;
    }
    #faq h4 {
    	font-size: 15px;
    	margin-bottom: 0;
    	font-weight: 500;
    }
     #faq h4 i {
     	margin-right: 5px;
     }
    #faq p {
    	font-size: 13px;
    	line-height: 18px;
    	display: block;
    	padding-left: 23px;
    	padding-bottom: 20px;
    	opacity: .7
    }
</style>
<link href="<?php echo plugins_url( 'css/font-awesome.css', __FILE__ ); ?>" rel="stylesheet">
<div class="api postbox">
	<div class="apiHeader">
		<img src="<?php echo plugins_url( 'logo.png', __FILE__ ); ?>" alt="">
		<a href="https://www.clickfunnels.com/users/sign_in" target="_blank" class=""><i class="fa fa-bars"></i> My Account</a>
		<br clear="all">
	</div>
	<div class="apiSubHeader">
		<h2>ClickFunnels Plugin Support</h2>
	</div>
	<div id="apiSettings">
        <h2 style="margin-top: 10px"><strong>Frequently Asked Questions</strong></h2>
        <p style="opacity: .7">Quickly find out about all the features to get the most out of using WordPress to show your ClickFunnels pages. You can also go to the <a href="https://support.clickfunnels.com/support/home" target="_blank">knowledge base</a> to learn more about ClickFunnels.</p>
        <hr style="border: none; border-bottom: 1px solid #ECEEEF; margin: 20px 0">	
        <div id="faq">
        	<h4><i class="fa fa-question-circle"></i> How and where do I get Authentication Key?</h4>
        	<p>To access your Authentication Token go to your ClickFunnels Members area and choose <a href="https://app.clickfunnels.com/users/edit" target="_blank">My Account > Settings</a> and you will find your <em>unique</em> authenication key. </p>
        	
        	<h4><i class="fa fa-question-circle"></i> What is a Custom URL?</h4>
        	<p>A custom URL is the web address that will point to your ClickFunnels page. For example, "www.mydomain.com/signup" could point to any of your ClickFunnels pages such as an opt-in page.</p>

        	<h4><i class="fa fa-question-circle"></i> How do I Set Homepage or 404 Page?</h4>
        	<p>To set a page to show as the home page just select any of your ClickFunnels pages (within the plugin) and select "Home Page" and hit save. <em>You can only have one page set to Home page or 404 page at a time.</em></p>

        	<h4><i class="fa fa-question-circle"></i> Do I Need a ClickFunnels Account?</h4>
        	<p><strong>Yes.</strong> A clickfunnels account is required to use this plugin. If you are not already a member you can <a href="https://clickfunnels.com/" target="_blank">sign up for your account</a> and transform the way you run your online business.</p>
        </div>
        <br clear="both">
       </div>
	<div id="apiFooter">
		<a class="button button-secondary" style="float: left; margin-right: 10px" type="submit" href="<?php echo admin_url( 'edit.php?post_type=clickfunnels' );?>"><i class="fa fa-file-text-o"></i> Pages</a>
               <a class="button button-secondary" style="float: left; margin-right: 10px" type="submit" href="<?php echo admin_url( 'post-new.php?post_type=clickfunnels' );?>"><i class="fa fa-plus-circle"></i> Add New</a>
		<a href="edit.php?post_type=clickfunnels&page=cf_api" class="button button-default" style="float: left"><i class="fa fa-cog"></i> Settings</a>
		<a href="https://support.clickfunnels.com/support/home" target="_blank" class="button button-primary" style="float: right"><i class="fa fa-life-ring"></i> Visit Knowledge Base</a>
      		
      		<br clear="both" /><br>
	</div>
	<?php include('footer.php'); ?>
</div>

<script>
	(function($) {
		setTimeout(function() {
			$('#errorMessage').fadeOut();
		}, 1500);
		var specificFunnel = 'https://api.clickfunnels.com/funnels.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';
		$.getJSON(specificFunnel, function(data) {
		   $('.checkSuccess').html('<i class="fa fa-check successGreen"></i>');
		  }).fail(function(jqXHR) {
	     		$('.checkSuccess').html('<i class="fa fa-times errorRed"></i>');
	     		$('.badAPI').show();
		  });
	})(jQuery);
</script>
