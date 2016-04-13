/*  Select  */
SELECT * FROM `wp_tzs_shipments` WHERE `from_rid`=76037991 or `to_rid`=76037991;
SELECT * FROM `wp_tzs_trucks` WHERE `from_rid`=76037991 or `to_rid`=76037991;
SELECT * FROM `wp_tzs_products` WHERE `from_rid`=76037991;
SELECT * FROM `wp_tzs_cities` WHERE `region_id`=76037991;
SELECT * FROM `wp_tzs_regions` WHERE `region_id`=76037991;
SELECT * FROM `wp_tzs_city_ids` WHERE `ids` like '%76037991%';

/*  Delete  */
DELETE FROM `wp_tzs_shipments` WHERE `from_rid`=76037991 or `to_rid`=76037991;
DELETE FROM `wp_tzs_trucks` WHERE `from_rid`=76037991 or `to_rid`=76037991;
DELETE FROM `wp_tzs_products` WHERE `from_rid`=76037991;
DELETE FROM `wp_tzs_cities` WHERE `region_id`=76037991;
DELETE FROM `wp_tzs_regions` WHERE `region_id`=76037991;
DELETE FROM `wp_tzs_city_ids` WHERE `ids` like '%76037991%';

/*  Restructure  */
ALTER TABLE `wp_tzs_shipments` ADD `dt_pickup` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `wp_tzs_shipments` ADD `top_loading` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_shipments` ADD `side_loading` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_shipments` ADD `back_loading` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_shipments` ADD `full_movable` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_shipments` ADD `remove_cross` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_shipments` ADD `remove_racks` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_shipments` ADD `without_gate` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_shipments` ADD `path_segment_cities` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `wp_tzs_shipments` ADD `path_segment_distances` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `wp_tzs_shipments` CHANGE `cost` `cost` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `wp_tzs_trucks` ADD `dt_pickup` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `wp_tzs_trucks` ADD `top_loading` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_trucks` ADD `side_loading` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_trucks` ADD `back_loading` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_trucks` ADD `full_movable` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_trucks` ADD `remove_cross` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_trucks` ADD `remove_racks` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_trucks` ADD `without_gate` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `wp_tzs_trucks` ADD `path_segment_cities` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `wp_tzs_trucks` ADD `path_segment_distances` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `wp_tzs_trucks` CHANGE `cost` `cost` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `wp_tzs_products` ADD `dt_pickup` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

/*  Update  */
UPDATE `wp_tzs_shipments` SET `path_segment_cities` = CONCAT_WS(";", `sh_city_from`, `sh_city_to`)  WHERE `path_segment_cities` IS NULL;
UPDATE `wp_tzs_trucks` SET `path_segment_cities` = CONCAT_WS(";", `tr_city_from`, `tr_city_to`)  WHERE `path_segment_cities` IS NULL;
