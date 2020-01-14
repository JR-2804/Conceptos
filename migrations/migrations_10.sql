ALTER TABLE `pre_facture_product` ADD COLUMN `state`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `product_price`;

ALTER TABLE `pre_facture_card` ADD COLUMN `state`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `price`;
