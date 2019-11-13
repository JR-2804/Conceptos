ALTER TABLE `product` ADD COLUMN `is_faucet`  tinyint(1) NOT NULL AFTER `priority`;
ALTER TABLE `product` ADD COLUMN `is_grill`  tinyint(1) NOT NULL AFTER `is_faucet`;
ALTER TABLE `product` ADD COLUMN `is_shelf`  tinyint(1) NOT NULL AFTER `is_grill`;
ALTER TABLE `product` ADD COLUMN `is_desk`  tinyint(1) NOT NULL AFTER `is_shelf`;
ALTER TABLE `product` ADD COLUMN `is_bookcase`  tinyint(1) NOT NULL AFTER `is_desk`;
ALTER TABLE `product` ADD COLUMN `is_comoda`  tinyint(1) NOT NULL AFTER `is_bookcase`;
ALTER TABLE `product` ADD COLUMN `is_repisa`  tinyint(1) NOT NULL AFTER `is_comoda`;
