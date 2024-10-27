<?php
/*
Plugin Name: Amazon S3 URL Generator
Plugin URI: http://codepolice.net/2008/12/08/generate-expiring-urls-for-amazon-s3-via-a-wordpress-plugin/
Description: Allows you to add a Querystring protected URL to a object in a Amazon S3 bucket.
Version: 0.6
Date: 2010-04-08
Author: ola@27kilobyte.se
Author URI: http://codepolice.net
Contributors: Made by stuckish@27kilobyte.se, Based on script by George Pearce (http://www.fused.org.uk/2008/08/s3-query-string-authentication-generator/comment-page-1/#comment-5501)
*/ 

function s3Url($text) {
	$AWS_S3_KEY = get_option('aws_s3_key');
	$AWS_S3_SECRET = get_option('aws_s3_secret');;
    $tag_pattern = '/(\[S3 bucket\=(.*?)\ text\=(.*?)\](.*?)\[\/S3\])/i';

	// if you want to remove the password (ish) protection, remove the above and the } else { exit; } from the bottom of the script.
	define("AWS_S3_KEY", $AWS_S3_KEY); // replace this with your AWS S3 key
	define("AWS_S3_SECRET", $AWS_S3_SECRET); // replace this with your secret key.
	$expires = time()+get_option('expire_seconds');

	if (preg_match_all ($tag_pattern, $text, $matches)) {
		
		for ($m=0; $m<count($matches[0]); $m++) {
			$bucket = $matches[2][$m];
			$link_text = $matches[3][$m];
			$resource = $matches[4][$m];
		
		  $string_to_sign = "GET\n\n\n$expires\n/".str_replace(".s3.amazonaws.com","",$bucket)."/$resource";
		
			//$string_to_sign = "GET\n\n\n{$expires}\n/{$bucket}/{$resource}"; 
			$signature = urlencode(base64_encode((hash_hmac("sha1", utf8_encode($string_to_sign), AWS_S3_SECRET, TRUE))));
			
			$authentication_params = "AWSAccessKeyId=".AWS_S3_KEY;
			$authentication_params.= "&Expires={$expires}";
			$authentication_params.= "&Signature={$signature}";
			
			$tag_pattern_match = "/(\[S3 bucket\=(.*?)\ text\={$link_text}\]{$resource}\[\/S3\])/i";
			
			if(strlen($link_text) == 0)
			{
				$link = "http://{$bucket}/{$resource}?{$authentication_params}";
			}
			else
			{
				$link = "<a href='http://{$bucket}/{$resource}?{$authentication_params}'>{$link_text}</a>";
			}
			
			$text = preg_replace($tag_pattern_match,$link,$text);
		}
	}

	return $text;
}

function s3_url_generator_menu() {
  add_options_page('S3 URL Generator Options', 'S3 URL Generator', 8, __FILE__, 's3_url_generator_options');
}

function s3_url_generator_options() {
  ?>
<div class="wrap">
<h2>Amazon S3 URL Generator</h2>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">Amazon S3 Key</th>
		<td><input type="text" name="aws_s3_key" style="width: 400px;" value="<?php echo get_option('aws_s3_key'); ?>" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">Amazon S3 Secret</th>
		<td><input type="text" name="aws_s3_secret" style="width: 400px;" value="<?php echo get_option('aws_s3_secret'); ?>" /></td>
	</tr>
	<tr valign="top">
		<th scope="row">Expire (In seconds)</th>
		<td><input type="text" name="expire_seconds" style="width: 400px;" value="<?php echo get_option('expire_seconds'); ?>" /></td>
	</tr>
</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="aws_s3_key,aws_s3_secret,expire_seconds" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
</p>

	<h3>Usage</h3>
	<p>
	[S3 bucket=yourbucket text=your link text]the_object_name.txt[/S3]
	</p>
	<p>
	Read more at my blog - <a target="_blank" href="http://www.codepolice.net/2008/12/08/generate-expiring-urls-for-amazon-s3-via-a-wordpress-plugin/"> codepolice.net/2008/12/08/generate-expiring-urls-for-amazon-s3-via-a-wordpress-plugin/</a>
</p>
<p>
	This is just a "exprimental" version. If you have suggestions on how to improve this plugin pleace comment in the blog.
	</p>

</form>
</div>
  <?php
}
add_action('admin_menu', 's3_url_generator_menu');
add_filter('the_content', 's3Url');
?>