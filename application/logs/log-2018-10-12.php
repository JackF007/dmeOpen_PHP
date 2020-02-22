<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-10-12 10:57:38 --> Severity: Compile Error --> Cannot use isset() on the result of an expression (you can use "null !== expression" instead) /var/www/html/gyacomo/application/modules/api_panel/models/TweetModel.php 37
ERROR - 2018-10-12 11:04:15 --> Severity: Notice --> Undefined variable: data /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 75
ERROR - 2018-10-12 11:06:07 --> Severity: Notice --> Undefined variable: data /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 75
ERROR - 2018-10-12 12:47:52 --> Query error: Unknown column 'created_time' in 'field list' - Invalid query: INSERT INTO `user_post_comment` (`user_id`, `post_id`, `comment`, `created_time`, `update_time`, `status`) VALUES ('3', '2', 'awesome', 1539328672, 1539328672, 1)
ERROR - 2018-10-12 13:41:43 --> Query error: Unknown column 'update_time' in 'field list' - Invalid query: UPDATE `user_post_like` SET `status` = 2, `update_time` = 1539331903
WHERE `post_id` = '1'
AND `user_id` = '2'
ERROR - 2018-10-12 15:01:30 --> 404 Page Not Found: ../modules/api_panel/controllers//index
ERROR - 2018-10-12 15:01:43 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:03:07 --> Severity: error --> Exception: Call to undefined method Tweetmodel::get_json_data() /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 226
ERROR - 2018-10-12 15:05:38 --> Severity: Notice --> Array to string conversion /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:08:23 --> 404 Page Not Found: ../modules/api_panel/controllers/Tweet/get_json
ERROR - 2018-10-12 15:09:15 --> Severity: error --> Exception: syntax error, unexpected 'a' (T_STRING), expecting '(' /var/www/html/gyacomo/application/modules/api_panel/models/TweetModel.php 175
ERROR - 2018-10-12 15:09:38 --> Query error: Table 'gyacomo.user_post_reports' doesn't exist - Invalid query: SELECT `comment`
FROM `user_post_reports`
ERROR - 2018-10-12 15:12:04 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:13:40 --> Severity: Notice --> Array to string conversion /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:14:57 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:16:04 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:16:27 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:16:28 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:17:03 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 228
ERROR - 2018-10-12 15:17:23 --> Severity: Notice --> Array to string conversion /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:17:23 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 228
ERROR - 2018-10-12 15:18:38 --> Severity: 4096 --> Object of class stdClass could not be converted to string /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:18:38 --> Severity: Warning --> json_decode() expects parameter 1 to be string, object given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 228
ERROR - 2018-10-12 15:19:58 --> Severity: 4096 --> Object of class stdClass could not be converted to string /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:19:58 --> Severity: Warning --> json_decode() expects parameter 1 to be string, object given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 228
ERROR - 2018-10-12 15:20:00 --> Severity: 4096 --> Object of class stdClass could not be converted to string /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 227
ERROR - 2018-10-12 15:20:00 --> Severity: Warning --> json_decode() expects parameter 1 to be string, object given /var/www/html/gyacomo/application/modules/api_panel/controllers/Tweet.php 228
ERROR - 2018-10-12 15:29:43 --> 404 Page Not Found: ../modules/api_panel/controllers/Tweet/get_json_data
ERROR - 2018-10-12 18:53:48 --> 404 Page Not Found: ../modules/auth_panel/controllers/Login/img
