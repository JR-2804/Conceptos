-- CREATE TABLE `product_metanames` (
--   `id`  int(11) NOT NULL AUTO_INCREMENT ,
--   `product_id`  int(11) NULL DEFAULT NULL ,
--   `name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
--   PRIMARY KEY (`id`),
--   CONSTRAINT `FK_8E2EAAC64584665F` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
--   INDEX `IDX_8E2EAAC64584665F` (`product_id`) USING BTREE
-- );

ALTER TABLE `product`
  ADD COLUMN `width_left`  double NULL DEFAULT NULL AFTER `creation_date`,
  ADD COLUMN `width_right`  double NULL DEFAULT NULL AFTER `width_left`,
  ADD COLUMN `width`  double NULL DEFAULT NULL AFTER `width_right`,
  ADD COLUMN `height_min`  double NULL DEFAULT NULL AFTER `width`,
  ADD COLUMN `height_max`  double NULL DEFAULT NULL AFTER `height_min`,
  ADD COLUMN `height`  double NULL DEFAULT NULL AFTER `height_max`,
  ADD COLUMN `deep_min`  double NULL DEFAULT NULL AFTER `height`,
  ADD COLUMN `deep_max`  double NULL DEFAULT NULL AFTER `deep_min`,
  ADD COLUMN `deep`  double NULL DEFAULT NULL AFTER `deep_max`,
  ADD COLUMN `length`  double NULL DEFAULT NULL AFTER `deep`,
  ADD COLUMN `diameter`  double NULL DEFAULT NULL AFTER `length`,
  ADD COLUMN `max_load`  double NULL DEFAULT NULL AFTER `diameter`,
  ADD COLUMN `area`  double NULL DEFAULT NULL AFTER `max_load`,
  ADD COLUMN `thickness`  double NULL DEFAULT NULL AFTER `area`,
  ADD COLUMN `volume`  double NULL DEFAULT NULL AFTER `thickness`,
  ADD COLUMN `surface_density`  double NULL DEFAULT NULL AFTER `volume`;
