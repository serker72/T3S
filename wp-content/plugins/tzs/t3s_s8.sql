ALTER TABLE `wp_tzs_shipments` ADD `path_segment_cities` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `wp_tzs_shipments` ADD `path_segment_distances` VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE `wp_tzs_trucks` ADD `path_segment_cities` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `wp_tzs_trucks` ADD `path_segment_distances` VARCHAR(255) NULL DEFAULT NULL;
