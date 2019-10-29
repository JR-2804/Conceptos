ALTER TABLE `pre_facture_product` ADD COLUMN `product_price` int(11) NOT NULL DEFAULT 0 AFTER `is_ariplane_mattress`;
ALTER TABLE `facture_product` ADD COLUMN `product_price` int(11) NOT NULL DEFAULT 0 AFTER `is_ariplane_mattress`;
ALTER TABLE `request_product` ADD COLUMN `product_price` int(11) NOT NULL DEFAULT 0 AFTER `is_ariplane_mattress`;
