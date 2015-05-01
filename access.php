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
</style>
<link href="<?php echo plugins_url( 'css/font-awesome.css', __FILE__ ); ?>" rel="stylesheet">
<div class="api postbox">
	<div class="apiHeader">
		<img src="<?php echo plugins_url( 'logo.png', __FILE__ ); ?>" alt="">
		<a href="https://www.clickfunnels.com/users/sign_in" target="_blank" class=""><i class="fa fa-bars"></i> My Account</a>
		<br clear="all">
	</div>
	<div class="apiSubHeader">
		<h2>Setup Your ClickFunnels API Settings</h2>
	</div>
    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
	<?php
		if ( 'yes' == $_REQUEST['save_api'] ) {
			if ($_POST['clickfunnels_api_email'] == '') {
				echo "<div id='errorMessage' class='error'>Uh oh, you didn't add an email address.</div>";
			}
			else if ($_POST['clickfunnels_api_auth'] == '') {
				echo "<div id='errorMessage' class='error'>Please add API Key.</div>";
			}
			else {
				echo "<div id='errorMessage' class=''>Successlly Updated API Settings</div>";
				update_option( 'clickfunnels_api_email', $_POST['clickfunnels_api_email'] );
				update_option( 'clickfunnels_api_auth', $_POST['clickfunnels_api_auth'] );
				update_option( 'clickfunnels_siteURL', $_POST['clickfunnels_siteURL'] );
				update_option( 'clickfunnels_404Redirect', $_POST['clickfunnels_404Redirect'] );
			}
		}
	?>
	<div id="errorMessage" class="badAPI"><i class="fa fa-times"></i> Uh oh, looks like either your Email or API Auth Token is Incorrect</div>
	<div id="apiSettings">
        <div class="leftSide">
        	<input type="hidden" class="form-control" name="save_api" value="yes" />
	        <h3>Account Email: <span class="checkSuccess"></span></h3>
	        <input type="text" name="clickfunnels_api_email" id="clickfunnels_api_email" placeholder="johndoe@gmail.com" value="<?php echo get_option( 'clickfunnels_api_email' ); ?>" />
	        <h3>Authentication Token:  <span class="checkSuccess"></span></h3>
	        <input type="text" name="clickfunnels_api_auth" id="clickfunnels_api_auth" placeholder="C5c86J28WbxuxxUqN59z5D" value="<?php echo get_option( 'clickfunnels_api_auth' ); ?>" />
	        <h3>Website URL:   <span class="checkSuccess"><i class="fa fa-check successGreen"></i></span></h3>
	        <input type="text" name="clickfunnels_siteURL" id="clickfunnels_siteURL" placeholder="http://yourdomain.com/" value="<?php if (get_option( 'clickfunnels_siteURL' ) != '') { echo get_option( 'clickfunnels_siteURL' ); } else { echo get_site_url(); } ?>" />
	        <!-- <h3><input type="checkbox" value="yesRedirect" <?php if (get_option( 'clickfunnels_404Redirect' ) != '') { echo 'checked'; } ?> name="clickfunnels_404Redirect" style="width: auto;float: left;margin-top:-2px;margin-right: 8px;" /> 404 Redirect to Homepage (LinkSaver)</h3> -->
        </div>
        <div class="rightSide">
        	<p>To access your Authentication Token go to your ClickFunnels Members area and choose <a href="https://app.clickfunnels.com/users/edit" target="_blank">My Account > Settings</a> and you will find your API information.</p>

        	<br><br><hr><br><br>
        	<p>Change the website URL only if you have WordPress installed in a sub directory.</p>
        </div>
        <br clear="both">
       </div>
		<div id="apiFooter">
			<a class="button button-secondary" style="float: left; margin-right: 10px" type="submit" href="<?php echo admin_url( 'edit.php?post_type=clickfunnels' );?>"><i class="fa fa-file-text-o"></i> Pages</a>
                 <a class="button button-secondary" style="float: left; margin-right: 10px" type="submit" href="<?php echo admin_url( 'post-new.php?post_type=clickfunnels' );?>"><i class="fa fa-plus-circle"></i> Add New</a>
			<a href="edit.php?post_type=clickfunnels&page=clickfunnels_support" class="button button-default" style="float: left"><i class="fa fa-life-ring"></i> Support</a>
        		<button class="button-primary" style="float: right;margin-top: 5px;"><i class="fa fa-check-circle"></i> Save Settings</button>
        		<br clear="both" /><br>
		</div>
    </form>
    <?php include('footer.php'); ?>
   
</div>
 <br clear="all">
    <span style="font-size: 11px; padding-left: 10px;"><a href="#" id="showDataTesting" style="text-decoration: none; color: #999"><i class="fa fa-dashboard"></i> Show Plugin Developer Data</a></span>
<?php include('admin_testing.php'); ?>

