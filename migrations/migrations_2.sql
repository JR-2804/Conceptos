ALTER TABLE `product` ADD COLUMN `is_mattress`  tinyint(11) NOT NULL AFTER `number_of_packages`;
ALTER TABLE `product` ADD COLUMN `is_ariplane_mattress`  tinyint(11) NOT NULL AFTER `is_mattress`;

ALTER TABLE `pre_facture_product` ADD COLUMN `is_ariplane_mattress`  tinyint(1) NOT NULL DEFAULT 0 AFTER `is_ariplane_forniture`;
ALTER TABLE `facture_product` ADD COLUMN `is_ariplane_mattress`  tinyint(1) NOT NULL DEFAULT 0 AFTER `is_ariplane_forniture`;
ALTER TABLE `request_product` ADD COLUMN `is_ariplane_mattress`  tinyint(1) NOT NULL DEFAULT 0 AFTER `is_ariplane_forniture`;
