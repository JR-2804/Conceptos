CREATE TABLE `combo_product` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `parent_product_id`  int(11) NULL DEFAULT NULL ,
  `product_id`  int(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_8E1EAAC34584665D` FOREIGN KEY (`parent_product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_8E1EAAC34584665D` (`product_id`) USING BTREE,
  CONSTRAINT `FK_8E1EAAC34584665F` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_8E1EAAC34584665F` (`product_id`) USING BTREE
);

