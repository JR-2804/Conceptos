ALTER TABLE `pre_facture_product` ADD COLUMN `product_price` int(11) NOT NULL DEFAULT 0 AFTER `is_ariplane_mattress`;
ALTER TABLE `facture_product` ADD COLUMN `product_price` int(11) NOT NULL DEFAULT 0 AFTER `is_ariplane_mattress`;
ALTER TABLE `request_product` ADD COLUMN `product_price` int(11) NOT NULL DEFAULT 0 AFTER `is_ariplane_mattress`;

ALTER TABLE `request_product` DROP FOREIGN KEY `FK_F96E76FA4584665A`;
ALTER TABLE `request_product` DROP FOREIGN KEY `FK_F96E76FA53C674EE`;
ALTER TABLE `request_product` ADD CONSTRAINT `FK_F96E76FA4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE `request_product` ADD CONSTRAINT `FK_F96E76FA53C674EE` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

ALTER TABLE `pre_facture_product` DROP FOREIGN KEY `FK_F96E76FA4584665C`;
ALTER TABLE `pre_facture_product` DROP FOREIGN KEY `FK_F96E76FA53C674FF`;
ALTER TABLE `pre_facture_product` ADD CONSTRAINT `FK_F96E76FA4584665C` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE `pre_facture_product` ADD CONSTRAINT `FK_F96E76FA53C674FF` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

ALTER TABLE `facture_product` DROP FOREIGN KEY `FK_F96E76FA4584665B`;
ALTER TABLE `facture_product` DROP FOREIGN KEY `FK_F96E76FA53C674EF`;
ALTER TABLE `facture_product` ADD CONSTRAINT `FK_F96E76FA4584665B` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE `facture_product` ADD CONSTRAINT `FK_F96E76FA53C674EF` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

CREATE TABLE `offer_category` (
  `offer_id`  int(11) NOT NULL,
  `category_id`  int(11) NOT NULL,
  PRIMARY KEY (`offer_id`, `category_id`),
  CONSTRAINT `FK_7242C2A453C574EE` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `FK_7242C2A43584665A` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_7242C2A453C574EE` (`offer_id`) USING BTREE,
  INDEX `IDX_7242C2A43584665A` (`category_id`) USING BTREE
);
