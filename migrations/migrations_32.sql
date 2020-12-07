DROP TABLE IF EXISTS `product_code`;
CREATE TABLE `product_code` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `product_id`  int(11) NULL DEFAULT NULL ,
  `code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_8E2EAAC75584665F` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_8E2EAAC75584665F` (`product_id`) USING BTREE
);

DROP TABLE IF EXISTS `product_color`;
CREATE TABLE `product_color` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `product_id`  int(11) NULL DEFAULT NULL ,
  `color`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_8E2EAAC76584665F` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_8E2EAAC76584665F` (`product_id`) USING BTREE
);

DROP TABLE IF EXISTS `product_material`;
CREATE TABLE `product_material` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `product_id`  int(11) NULL DEFAULT NULL ,
  `material`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_8E2EAAC77584665F` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_8E2EAAC77584665F` (`product_id`) USING BTREE
);

DROP TABLE IF EXISTS `product_room`;
CREATE TABLE `product_room` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `product_id`  int(11) NULL DEFAULT NULL ,
  `room`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_8E2EAAC78584665F` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_8E2EAAC78584665F` (`product_id`) USING BTREE
);

ALTER TABLE `product` ADD COLUMN `classification_id`  int(11) NULL DEFAULT NULL AFTER `is_disabled`;
ALTER TABLE `product` ADD CONSTRAINT `FK_9363FFCE4584665A` FOREIGN KEY (`classification_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
