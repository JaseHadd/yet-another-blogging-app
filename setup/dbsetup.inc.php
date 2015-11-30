<?php
$user_login_table = "
CREATE TABLE {$prefix}user_login (
  login_id int AUTO_INCREMENT,
  password binary(60) NOT NULL,
  email_address varchar(254) NOT NULL,
  PRIMARY KEY (login_id)
) CHARACTER SET=utf8mb4";

$user_info_table = "
CREATE TABLE {$prefix}user_info (
  user_id int AUTO_INCREMENT,
  login_id int NOT NULL,
  first_name varchar(40),
  other_name varchar(40),
  last_name varchar(40),
  display_name varchar(122),
  PRIMARY KEY (user_id),
  FOREIGN KEY (login_id) REFERENCES {$prefix}user_login(login_id)
) CHARACTER SET=utf8mb4";

$category_table = "
CREATE TABLE {$prefix}categories(
  category_id int AUTO_INCREMENT,
  name varchar(20)
) CHARACTER SET=utf8mb4";
  
$post_table = "
CREATE TABLE {$prefix}posts (
  post_id int AUTO_INCREMENT,
  created_time datetime NOT NULL,
  modified_time datetime NOT NULL,
  author_id int NOT NULL,
  category_id int NOT NULL,
  body text NOT NULL,
  PRIMARY KEY (post_id),
  FOREIGN KEY (author_id) REFERENCES {$prefix}user_info(user_id),
  FOREIGN KEY (category_id) REFERENCES {$prefix}categories(category_id)
) CHARACTER SET=utf8mb4";

$mysql_queries = array(
  $user_login_table,
  $user_info_table,
  $category_table,
  $post_table,
);
?>