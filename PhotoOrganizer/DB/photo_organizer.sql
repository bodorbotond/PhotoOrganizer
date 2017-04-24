
drop database if exists `photo_organizer`;

create database if not exists `photo_organizer` default character set utf8;

use `photo_organizer`;

create table if not exists `users`(
  `user_id` tinyint(5) unsigned not null auto_increment,
  `user_name` varchar(50) not null unique,
  `e_mail` varchar(50) not null unique,
  `password` varchar(50) not null,
  `profile_picture_path` varchar(200) default null,
  `auth_key` varchar(30) not null,
  `user_status` varchar(10) not null,
  `user_token` varchar(6) not null,
  primary key(`user_id`)
);
  
  create table if not exists `photos`(
  `photo_id` tinyint(5) unsigned not null auto_increment,
  `user_id` tinyint(5) unsigned not null,
  `photo_name` varchar(50) not null,
  `photo_type` varchar(25) not null,
  `photo_path` blob not null,
  `photo_size` varchar(25) not null default '',
  `photo_height` int(8) not null,
  `photo_width` int(8) not null,
  primary key(`photo_id`),
  constraint `UserPhotoId` foreign key (`user_id`) references `users` (`user_id`)
);