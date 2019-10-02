CREATE TABLE `facture` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `client_id`  int(11) NULL DEFAULT NULL ,
  `date`  datetime NOT NULL ,
  `final_price`  double NOT NULL ,
  `transport_cost`  double NOT NULL ,
  `discount`  double NOT NULL ,
  `first_client_discount`  double NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_3B978F9F19EB6922` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX `IDX_3B978F9F19EB6922` (`client_id`) USING BTREE
);

CREATE TABLE `pre_facture` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `client_id`  int(11) NULL DEFAULT NULL ,
  `date`  datetime NOT NULL ,
  `final_price`  double NOT NULL ,
  `transport_cost`  double NOT NULL ,
  `discount`  double NOT NULL ,
  `first_client_discount`  double NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_3B978F9F19EB6923` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX `IDX_3B978F9F19EB6923` (`client_id`) USING BTREE
);

CREATE TABLE `facture_card` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `facture_id`  int(11) NULL DEFAULT NULL ,
  `count`  int(11) NOT NULL ,
  `price`  int(11) NOT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_69B3BF29427EB8A6` FOREIGN KEY (`facture_id`) REFERENCES `facture` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_69B3BF29427EB8A7` (`facture_id`) USING BTREE
);

CREATE TABLE `pre_facture_card` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `pre_facture_id`  int(11) NULL DEFAULT NULL ,
  `count`  int(11) NOT NULL ,
  `price`  int(11) NOT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_69B3BF29427EB8A7` FOREIGN KEY (`pre_facture_id`) REFERENCES `pre_facture` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_69B3BF29427EB8A7` (`pre_facture_id`) USING BTREE
);

CREATE TABLE `facture_product` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `product_id`  int(11) NULL DEFAULT NULL ,
  `facture_id`  int(11) NULL DEFAULT NULL ,
  `offer_id`  int(11) NULL DEFAULT NULL ,
  `count`  int(11) NOT NULL ,
  `is_ariplane_forniture`  tinyint(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_F96E76FA4584665B` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_F96E76FA427EB8A6` FOREIGN KEY (`facture_id`) REFERENCES `facture` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `FK_F96E76FA53C674EF` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX `IDX_F96E76FA4584665B` (`product_id`) USING BTREE ,
  INDEX `IDX_F96E76FA427EB8A6` (`facture_id`) USING BTREE ,
  INDEX `IDX_F96E76FA53C674EF` (`offer_id`) USING BTREE
);

CREATE TABLE `pre_facture_product` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `product_id`  int(11) NULL DEFAULT NULL ,
  `pre_facture_id`  int(11) NULL DEFAULT NULL ,
  `offer_id`  int(11) NULL DEFAULT NULL ,
  `count`  int(11) NOT NULL ,
  `is_ariplane_forniture`  tinyint(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_F96E76FA4584665C` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_F96E76FA427EB8A7` FOREIGN KEY (`pre_facture_id`) REFERENCES `pre_facture` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `FK_F96E76FA53C674FF` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX `IDX_F96E76FA4584665C` (`product_id`) USING BTREE ,
  INDEX `IDX_F96E76FA427EB8A7` (`pre_facture_id`) USING BTREE ,
  INDEX `IDX_F96E76FA53C674FF` (`offer_id`) USING BTREE
);

ALTER TABLE `request_product` DROP FOREIGN KEY `FK_F96E76FA427EB8A5`;
ALTER TABLE `request_product` ADD CONSTRAINT `FK_F96E76FA427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `request_card` DROP FOREIGN KEY `FK_69B3BF29427EB8A5`;
ALTER TABLE `request_card` ADD CONSTRAINT `FK_69B3BF29427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `product` ADD COLUMN `number_of_packages`  int(11) NULL AFTER `is_lamp`;
