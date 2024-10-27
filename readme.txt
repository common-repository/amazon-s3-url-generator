=== Amazon S3 Expiring URL Generator ===
Contributors: stuckish
Donate link: http://codepolice.net/
Tags: Amazon S3,AWS,Expiring URL,URL,Post,links,page
Requires at least: 2.0
Tested up to: 2.9
Stable tag: 0.6

Generate a Amazon S3 expiring URL based on your key and secret. Prevents hotlinking to your media.

== Description ==

Generate a Amazon S3 expiring URL based on your key and secret. Prevents hotlinking to your media.

Change log:

Changes 0.6
Fixed a bug reported bu lots of user on my blog. Emile Bourquin came up with a solution. 
I have tested it and it still works for me (with CNAME buckets, people who do not use CNAME buckets have had problems). 
Please comment on the blog if you have any issues.
http://codepolice.net/2008/12/08/generate-expiring-urls-for-amazon-s3-via-a-wordpress-plugin/comment-page-1/#comment-933

Changes 0.5
- Added expire option

Changes 0.4
- Forgot some debug code that i removed now.

== Installation ==

1. Upload 'amazon-s3-url-generator.php' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use [S3 bucket=yourbucket text=your link text]the_object_name.txt[/S3] in a post or page to generate a link.
