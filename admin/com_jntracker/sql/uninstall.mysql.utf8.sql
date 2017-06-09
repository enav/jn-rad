DROP TABLE IF EXISTS `#__jntracker_projects`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_jntracker.%');