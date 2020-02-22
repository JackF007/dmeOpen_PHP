<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-01-09 12:57:46 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '`
AND `category_id` = '2'
AND `sub_category_status` =0' at line 3 - Invalid query: SELECT `category_id`, `sub_category_id`, `sub_category_name`, concat("http://54.82.133.166/images/sub_category/", sub_category_logo)sub_category_logo, `sub_category_creation_time`, `sub_category_update_time`, `sub_category_status`
FROM `sub_category_list`
WHERE `sub_category_name` like "%written `written%"`
AND `category_id` = '2'
AND `sub_category_status` =0
ERROR - 2019-01-09 16:10:32 --> 404 Page Not Found: ../modules/auth_panel/controllers/Login/img
ERROR - 2019-01-09 16:14:58 --> 404 Page Not Found: ../modules/auth_panel/controllers/Admin/img
ERROR - 2019-01-09 16:17:40 --> 404 Page Not Found: ../modules/auth_panel/controllers/Login/img
ERROR - 2019-01-09 16:18:06 --> 404 Page Not Found: ../modules/auth_panel/controllers/Admin/img
ERROR - 2019-01-09 16:54:50 --> 404 Page Not Found: ../modules/auth_panel/controllers/Category/img
ERROR - 2019-01-09 18:09:07 --> 404 Page Not Found: ../modules/auth_panel/controllers/Category/img
ERROR - 2019-01-09 22:25:33 --> 404 Page Not Found: ../modules/auth_panel/controllers/Login/img
ERROR - 2019-01-09 22:25:51 --> 404 Page Not Found: ../modules/auth_panel/controllers/Admin/img
ERROR - 2019-01-09 22:35:00 --> 404 Page Not Found: ../modules/auth_panel/controllers/Login/img
ERROR - 2019-01-09 22:35:35 --> 404 Page Not Found: ../modules/auth_panel/controllers/Admin/img
ERROR - 2019-01-09 22:35:42 --> 404 Page Not Found: ../modules/auth_panel/controllers/Admin/Category
ERROR - 2019-01-09 22:35:47 --> 404 Page Not Found: ../modules/auth_panel/controllers/Admin/Category
ERROR - 2019-01-09 22:35:50 --> 404 Page Not Found: ../modules/auth_panel/controllers/Admin/Category
ERROR - 2019-01-09 22:37:32 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '`
ORDER BY `post`.`update_date` DESC
 LIMIT 20' at line 5 - Invalid query: SELECT `post`.`id`, `post`.`user_id`, `post`.`post`, `post`.`tag`, CONCAT("http://54.82.133.166/uploads/tweet_photo/", post.photo) as photo, `post`.`created_date`, `post`.`update_date`
FROM `post`
JOIN `users` ON `users`.`id`=`post`.`user_id`
WHERE `post`.`status` = 1
AND `post` like "%test `test%"`
ORDER BY `post`.`update_date` DESC
 LIMIT 20
ERROR - 2019-01-09 23:22:02 --> 404 Page Not Found: ../modules/auth_panel/controllers/Category/img
