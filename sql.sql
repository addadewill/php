create database if not exists `php-app`
  default character set utf8
	collate utf8_unicode_ci;

create table if not exists `php-app`.`events` (
		`event_id` int(11) not null auto_increment,
		`event_title` varchar(80) default null,
		`event_desc` text,
		`event_start` timestamp not null default '0000-00-00 00:00:00',
		`event_end` timestamp not null default '0000-00-00 00:00:00',

    primary key (`event_id`),
		index (`event_start`)
		) engine=myisam character set utf8 collate utf8_unicode_ci;

insert into `php-app`.`events`
  (`event_title`, `event_desc`, `event_start`, `event_end`) values
	('New Year&#039;s Day', 'Happy New Year!',
	 '2015-01-01 00:00:00', '2015-01-01 23:59:59'),
	('Last Day of January', 'Last Day Of The Month', 
	 '2015-01-31 00:00:00', '2015-01-31 23:59:59');
