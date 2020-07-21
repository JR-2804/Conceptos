ALTER TABLE `product` ADD COLUMN `creation_date`  date NOT NULL AFTER `is_repisa`;
UPDATE product SET creation_date = DATE('2020-07-07')
