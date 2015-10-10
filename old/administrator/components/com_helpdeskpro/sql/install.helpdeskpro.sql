CREATE TABLE IF NOT EXISTS `#__helpdeskpro_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `access` tinyint(4) NOT NULL DEFAULT '0',
  `managers` varchar(255) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__helpdeskpro_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) DEFAULT NULL,
  `config_value` text,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__helpdeskpro_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `field_type` tinyint(3) unsigned DEFAULT NULL,
  `required` tinyint(3) unsigned DEFAULT NULL,
  `values` text,
  `default_values` text,
  `rows` tinyint(3) unsigned DEFAULT NULL,
  `cols` tinyint(3) unsigned DEFAULT NULL,
  `size` tinyint(3) unsigned DEFAULT NULL,
  `datatype_validation` tinyint(3) unsigned DEFAULT NULL,
  `css_class` varchar(100) DEFAULT NULL,
  `extra` varchar(255) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__helpdeskpro_field_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__helpdeskpro_field_value` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `field_value` tinytext,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__helpdeskpro_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `message` text,
  `attachments` tinytext,
  `original_filenames` tinytext,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__helpdeskpro_priorities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__helpdeskpro_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;
CREATE TABLE IF NOT EXISTS `#__helpdeskpro_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `attachments` varchar(255) DEFAULT NULL,
  `original_filenames` varchar(255) DEFAULT NULL,
  `ticket_code` varchar(15) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `priority_id` tinyint(3) unsigned DEFAULT NULL,
  `status_id` tinyint(3) unsigned DEFAULT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) CHARACTER SET `utf8`;