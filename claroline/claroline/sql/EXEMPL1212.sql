# phpMyAdmin MySQL-Dump
# version 2.2.6
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Sep 18, 2002 at 05:26 PM
# Server version: 3.23.47
# PHP Version: 4.1.2
# Database : `EXEMPL1212`
# --------------------------------------------------------

#
# Table structure for table `access`
#

CREATE TABLE access (
  access_id int(10) NOT NULL auto_increment,
  access_title varchar(20) default NULL,
  PRIMARY KEY  (access_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `accueil`
#

CREATE TABLE accueil (
  id int(11) NOT NULL auto_increment,
  rubrique varchar(100) default NULL,
  lien varchar(255) default NULL,
  image varchar(100) default NULL,
  visible tinyint(4) default NULL,
  admin varchar(200) default NULL,
  address varchar(120) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `agenda`
#

CREATE TABLE agenda (
  id int(11) NOT NULL auto_increment,
  titre varchar(200) default NULL,
  contenu text,
  day date NOT NULL default '0000-00-00',
  hour time NOT NULL default '00:00:00',
  lasting varchar(20) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banlist`
#

CREATE TABLE banlist (
  ban_id int(10) NOT NULL auto_increment,
  ban_userid int(10) default NULL,
  ban_ip varchar(16) default NULL,
  ban_start int(32) default NULL,
  ban_end int(50) default NULL,
  ban_time_type int(10) default NULL,
  PRIMARY KEY  (ban_id),
  KEY ban_id (ban_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `catagories`
#

CREATE TABLE catagories (
  cat_id int(10) NOT NULL auto_increment,
  cat_title varchar(100) default NULL,
  cat_order varchar(10) default NULL,
  PRIMARY KEY  (cat_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `config`
#

CREATE TABLE config (
  config_id int(10) NOT NULL auto_increment,
  sitename varchar(100) default NULL,
  allow_html int(2) default NULL,
  allow_bbcode int(2) default NULL,
  allow_sig int(2) default NULL,
  allow_namechange int(2) default '0',
  admin_passwd varchar(32) default NULL,
  selected int(2) NOT NULL default '0',
  posts_per_page int(10) default NULL,
  hot_threshold int(10) default NULL,
  topics_per_page int(10) default NULL,
  allow_theme_create int(10) default NULL,
  override_themes int(2) default '0',
  email_sig varchar(255) default NULL,
  email_from varchar(100) default NULL,
  default_lang varchar(255) default NULL,
  PRIMARY KEY  (config_id),
  UNIQUE KEY selected (selected)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `course_description`
#

CREATE TABLE course_description (
  id tinyint(3) unsigned NOT NULL default '0',
  title varchar(255) default NULL,
  content text,
  upDate datetime NOT NULL default '0000-00-00 00:00:00',
  UNIQUE KEY id (id)
) TYPE=MyISAM COMMENT='for course description tool';
# --------------------------------------------------------

#
# Table structure for table `disallow`
#

CREATE TABLE disallow (
  disallow_id int(10) NOT NULL auto_increment,
  disallow_username varchar(50) default NULL,
  PRIMARY KEY  (disallow_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `document`
#

CREATE TABLE document (
  id int(4) NOT NULL auto_increment,
  path varchar(255) NOT NULL default '',
  visibility char(1) NOT NULL default 'v',
  comment varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `exercice_question`
#

CREATE TABLE exercice_question (
  question_id int(11) NOT NULL default '0',
  exercice_id int(11) NOT NULL default '0',
  PRIMARY KEY  (question_id,exercice_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `exercices`
#

CREATE TABLE exercices (
  id tinyint(4) NOT NULL auto_increment,
  titre varchar(250) default NULL,
  description text,
  active tinyint(4) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `forum_access`
#

CREATE TABLE forum_access (
  forum_id int(10) NOT NULL default '0',
  user_id int(10) NOT NULL default '0',
  can_post tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (forum_id,user_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `forum_mods`
#

CREATE TABLE forum_mods (
  forum_id int(10) NOT NULL default '0',
  user_id int(10) NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `forums`
#

CREATE TABLE forums (
  forum_id int(10) NOT NULL auto_increment,
  forum_name varchar(150) default NULL,
  forum_desc text,
  forum_access int(10) default '1',
  forum_moderator int(10) default NULL,
  forum_topics int(10) NOT NULL default '0',
  forum_posts int(10) NOT NULL default '0',
  forum_last_post_id int(10) NOT NULL default '0',
  cat_id int(10) default NULL,
  forum_type int(10) default '0',
  PRIMARY KEY  (forum_id),
  KEY forum_last_post_id (forum_last_post_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `group_properties`
#

CREATE TABLE group_properties (
  id tinyint(4) NOT NULL auto_increment,
  self_registration tinyint(4) default '1',
  private tinyint(4) default '0',
  forum tinyint(4) default '1',
  document tinyint(4) default '1',
  wiki tinyint(4) default '0',
  agenda tinyint(4) default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `headermetafooter`
#

CREATE TABLE headermetafooter (
  header text,
  meta text,
  footer text
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `introduction`
#

CREATE TABLE introduction (
  id int(11) NOT NULL default '1',
  texte_intro text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `liens`
#

CREATE TABLE liens (
  id int(11) NOT NULL auto_increment,
  url varchar(150) default NULL,
  titre varchar(150) default NULL,
  description text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `liste_domaines`
#

CREATE TABLE liste_domaines (
  id int(11) NOT NULL auto_increment,
  domaine char(20) NOT NULL default '',
  description char(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `mc_scoring`
#

CREATE TABLE mc_scoring (
  id int(11) NOT NULL auto_increment,
  choice_count int(11) NOT NULL default '0',
  false_count int(11) NOT NULL default '0',
  score int(11) NOT NULL default '0',
  PRIMARY KEY  (id,choice_count,false_count)
) TYPE=MyISAM COMMENT='Content for grouped true/false score';
# --------------------------------------------------------

#
# Table structure for table `pages`
#

CREATE TABLE pages (
  id int(11) NOT NULL auto_increment,
  url varchar(200) default NULL,
  titre varchar(200) default NULL,
  description text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `posts`
#

CREATE TABLE posts (
  post_id int(10) NOT NULL auto_increment,
  topic_id int(10) NOT NULL default '0',
  forum_id int(10) NOT NULL default '0',
  poster_id int(10) NOT NULL default '0',
  post_time varchar(20) default NULL,
  poster_ip varchar(16) default NULL,
  nom varchar(30) default NULL,
  prenom varchar(30) default NULL,
  PRIMARY KEY  (post_id),
  KEY post_id (post_id),
  KEY forum_id (forum_id),
  KEY topic_id (topic_id),
  KEY poster_id (poster_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `posts_text`
#

CREATE TABLE posts_text (
  post_id int(10) NOT NULL default '0',
  post_text text,
  PRIMARY KEY  (post_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `priv_msgs`
#

CREATE TABLE priv_msgs (
  msg_id int(10) NOT NULL auto_increment,
  from_userid int(10) NOT NULL default '0',
  to_userid int(10) NOT NULL default '0',
  msg_time varchar(20) default NULL,
  poster_ip varchar(16) default NULL,
  msg_status int(10) default '0',
  msg_text text,
  PRIMARY KEY  (msg_id),
  KEY msg_id (msg_id),
  KEY to_userid (to_userid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `questions`
#

CREATE TABLE questions (
  id int(11) NOT NULL auto_increment,
  question text,
  description text,
  ponderation int(11) default NULL,
  q_position int(11) default NULL,
  type int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `ranks`
#

CREATE TABLE ranks (
  rank_id int(10) NOT NULL auto_increment,
  rank_title varchar(50) NOT NULL default '',
  rank_min int(10) NOT NULL default '0',
  rank_max int(10) NOT NULL default '0',
  rank_special int(2) default '0',
  rank_image varchar(255) default NULL,
  PRIMARY KEY  (rank_id),
  KEY rank_min (rank_min),
  KEY rank_max (rank_max)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `reponses`
#

CREATE TABLE reponses (
  id int(11) NOT NULL default '0',
  question_id int(11) NOT NULL default '0',
  reponse text,
  correct int(11) default NULL,
  comment text,
  r_position int(11) default NULL,
  PRIMARY KEY  (id,question_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `sessions`
#

CREATE TABLE sessions (
  sess_id int(10) unsigned NOT NULL default '0',
  user_id int(10) NOT NULL default '0',
  start_time int(10) unsigned NOT NULL default '0',
  remote_ip varchar(15) NOT NULL default '',
  PRIMARY KEY  (sess_id),
  KEY sess_id (sess_id),
  KEY start_time (start_time),
  KEY remote_ip (remote_ip)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `stat_accueil`
#

CREATE TABLE stat_accueil (
  id int(11) NOT NULL auto_increment,
  request char(100) NOT NULL default '',
  host char(100) NOT NULL default '',
  address char(100) NOT NULL default '',
  agent char(100) NOT NULL default '',
  date datetime default NULL,
  referer char(200) NOT NULL default '',
  country char(50) NOT NULL default '',
  provider char(100) NOT NULL default '',
  os char(50) NOT NULL default '',
  wb char(50) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `student_group`
#

CREATE TABLE student_group (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  description text,
  tutor int(11) default NULL,
  forumId int(11) default NULL,
  maxStudent int(11) NOT NULL default '0',
  secretDirectory varchar(30) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `themes`
#

CREATE TABLE themes (
  theme_id int(10) NOT NULL auto_increment,
  theme_name varchar(35) default NULL,
  bgcolor varchar(10) default NULL,
  textcolor varchar(10) default NULL,
  color1 varchar(10) default NULL,
  color2 varchar(10) default NULL,
  table_bgcolor varchar(10) default NULL,
  header_image varchar(50) default NULL,
  newtopic_image varchar(50) default NULL,
  reply_image varchar(50) default NULL,
  linkcolor varchar(15) default NULL,
  vlinkcolor varchar(15) default NULL,
  theme_default int(2) default '0',
  fontface varchar(100) default NULL,
  fontsize1 varchar(5) default NULL,
  fontsize2 varchar(5) default NULL,
  fontsize3 varchar(5) default NULL,
  fontsize4 varchar(5) default NULL,
  tablewidth varchar(10) default NULL,
  replylocked_image varchar(255) default NULL,
  PRIMARY KEY  (theme_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `topics`
#

CREATE TABLE topics (
  topic_id int(10) NOT NULL auto_increment,
  topic_title varchar(100) default NULL,
  topic_poster int(10) default NULL,
  topic_time varchar(20) default NULL,
  topic_views int(10) NOT NULL default '0',
  topic_replies int(10) NOT NULL default '0',
  topic_last_post_id int(10) NOT NULL default '0',
  forum_id int(10) NOT NULL default '0',
  topic_status int(10) NOT NULL default '0',
  topic_notify int(2) default '0',
  nom varchar(30) default NULL,
  prenom varchar(30) default NULL,
  PRIMARY KEY  (topic_id),
  KEY topic_id (topic_id),
  KEY forum_id (forum_id),
  KEY topic_last_post_id (topic_last_post_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `user_group`
#

CREATE TABLE user_group (
  id int(11) NOT NULL auto_increment,
  user int(11) NOT NULL default '0',
  team int(11) NOT NULL default '0',
  status int(11) NOT NULL default '0',
  role varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `users`
#

CREATE TABLE users (
  user_id int(10) NOT NULL auto_increment,
  username varchar(40) NOT NULL default '',
  user_regdate varchar(20) NOT NULL default '',
  user_password varchar(32) NOT NULL default '',
  user_email varchar(50) default NULL,
  user_icq varchar(15) default NULL,
  user_website varchar(100) default NULL,
  user_occ varchar(100) default NULL,
  user_from varchar(100) default NULL,
  user_intrest varchar(150) default NULL,
  user_sig varchar(255) default NULL,
  user_viewemail tinyint(2) default NULL,
  user_theme int(10) default NULL,
  user_aim varchar(18) default NULL,
  user_yim varchar(25) default NULL,
  user_msnm varchar(25) default NULL,
  user_posts int(10) default '0',
  user_attachsig int(2) default '0',
  user_desmile int(2) default '0',
  user_html int(2) default '0',
  user_bbcode int(2) default '0',
  user_rank int(10) default '0',
  user_level int(10) default '1',
  user_lang varchar(255) default NULL,
  user_actkey varchar(32) default NULL,
  user_newpasswd varchar(32) default NULL,
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `video`
#

CREATE TABLE video (
  id int(11) NOT NULL auto_increment,
  url varchar(200) default NULL,
  titre varchar(200) default NULL,
  description text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `whosonline`
#

CREATE TABLE whosonline (
  id int(3) NOT NULL auto_increment,
  ip varchar(255) default NULL,
  name varchar(255) default NULL,
  count varchar(255) default NULL,
  date varchar(255) default NULL,
  username varchar(40) default NULL,
  forum int(10) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `words`
#

CREATE TABLE words (
  word_id int(10) NOT NULL auto_increment,
  word varchar(100) default NULL,
  replacement varchar(100) default NULL,
  PRIMARY KEY  (word_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `work`
#

CREATE TABLE work (
  id int(11) NOT NULL auto_increment,
  url varchar(200) default NULL,
  titre varchar(200) default NULL,
  description varchar(250) default NULL,
  auteurs varchar(200) default NULL,
  active tinyint(1) default NULL,
  accepted tinyint(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

