
drop database if exists `photo_organizer`;

create database if not exists `photo_organizer` default character set utf8;

use `photo_organizer`;

create table if not exists `users`(
  `user_id` tinyint(5) unsigned not null auto_increment,
  `user_name` varchar(50) not null unique,
  `first_name` varchar(50) not null,
  `last_name` varchar(50) not null,
  `e_mail` varchar(50) not null unique,
  `recovery_e_mail` varchar(50) default null unique,
  `password` varchar(50) not null,
  `gender` varchar(10) not null,
  `profile_picture_path` varchar(200) default null,
  `auth_key` varchar(30) not null,
  `account_status` varchar(10) not null,
  `verification_key` varchar(6) not null,
  `two_step_verification` boolean not null default 0,
  primary key(`user_id`)
);
  
create table if not exists `photos`(
  `photo_id` tinyint(5) unsigned not null auto_increment,
  `user_id` tinyint(5) unsigned not null,  
  `photo_path` varchar(200) not null,
  `photo_extension` varchar(10) not null,
  `photo_size` int(10) not null,
  `photo_height` int(8) not null,
  `photo_width` int(8) not null,
  `photo_title` varchar(25),
  `photo_tag` varchar(25),
  `photo_description` varchar(200),
  `photo_visibility` varchar(10),
  `photo_upload_date` varchar(10) not null,
  primary key(`photo_id`),
  constraint `UserPhotoId` foreign key (`user_id`) references `users` (`user_id`)
);

create table if not exists `security_questions`(
  `question_id` tinyint(2) unsigned not null auto_increment,
  `question_text` varchar(200) not null,
  primary key(`question_id`)
);

create table if not exists `users_sequrity_questions`(
  `u_s_q_id` tinyint(5) unsigned not null auto_increment,
  `user_id` tinyint(5) unsigned not null,
  `question_id` tinyint(2) unsigned not null,
  `answer` varchar(200) not null,
  primary key(`u_s_q_id`),
  constraint `UserId` foreign key (`user_id`) references `users` (`user_id`),
  constraint `QuestionId` foreign key (`question_id`) references `security_questions` (`question_id`)
);

create table if not exists `old_passwords`(
  `old_password_id` tinyint(5) unsigned not null auto_increment,
  `user_id` tinyint(5) unsigned not null,
  `old_password` varchar(50) not null,
  primary key(`old_password_id`),
  constraint `UserIdOldPassword` foreign key (`user_id`) references `users` (`user_id`)
);


-- upload security_questons table

insert into security_questions (question_text) values ('What was the name of your elementaryprimary school?');
insert into security_questions (question_text) values ('What is your favorite book?');
insert into security_questions (question_text) values ('What is your favorite moovie?');
insert into security_questions (question_text) values ('What is your favorite food?');
insert into security_questions (question_text) values ('What is your pet\'s name?');
insert into security_questions (question_text) values ('What was your childhood nickname?');