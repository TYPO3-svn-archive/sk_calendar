#
# Table structure for table 'tx_skcalendar_category'
#
CREATE TABLE tx_skcalendar_category (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	title varchar(50) DEFAULT '' NOT NULL,
	icon blob NOT NULL,
	color varchar(7) DEFAULT '' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_skcalendar_organizer'
#
CREATE TABLE tx_skcalendar_organizer (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	name tinytext NOT NULL,
	link tinytext NOT NULL,
	logo blob NOT NULL,
	phone varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_skcalendar_location'
#
CREATE TABLE tx_skcalendar_location (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	title varchar(50) DEFAULT '' NOT NULL,
	link tinytext NOT NULL,
	street tinytext NOT NULL,
	zip int(11) DEFAULT '0' NOT NULL,
	city tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_skcalendar_targetgroup'
#
CREATE TABLE tx_skcalendar_targetgroup (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	title varchar(50) DEFAULT '' NOT NULL,
	icon blob NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_skcalendar_events'
#
CREATE TABLE tx_skcalendar_events (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(10) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	title varchar(128) DEFAULT '' NOT NULL,
	date int(11) DEFAULT '0' NOT NULL,
	start_time varchar(5) DEFAULT '' NOT NULL,
	end_time varchar(5) DEFAULT '' NOT NULL,
	wholeday tinyint(3) unsigned DEFAULT '0' NOT NULL,
	link tinytext NOT NULL,
	cost varchar(12) DEFAULT '0' NOT NULL,
	description text NOT NULL,
	image blob NOT NULL,
	highlight tinyint(3) unsigned DEFAULT '0' NOT NULL,
	fe_owner blob NOT NULL,
	pages blob NOT NULL,
	recurring int(11) unsigned DEFAULT '0' NOT NULL,
	recurr_until int(11) DEFAULT '0' NOT NULL,
	category blob NOT NULL,
	organizer blob NOT NULL,
	targetgroup blob NOT NULL,
	location blob NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_skcalendar_events'
#
CREATE TABLE tx_skcalendar_exeptions (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	event blob NOT NULL,
	exeptdate int(11) DEFAULT '0' NOT NULL,
	substitute_event blob NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);