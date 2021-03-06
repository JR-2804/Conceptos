ALTER TABLE `pre_facture` ADD COLUMN `request_id`  int(11) NULL DEFAULT NULL AFTER `first_client_discount`;
ALTER TABLE `pre_facture` ADD INDEX `IDX_69B3BF29427EB8B6` (`request_id`) USING BTREE;
ALTER TABLE `pre_facture` ADD CONSTRAINT `FK_69B3BF29427EB8B6` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `facture` ADD COLUMN `request_id`  int(11) NULL DEFAULT NULL AFTER `first_client_discount`;
ALTER TABLE `facture` ADD INDEX `IDX_69B3BF29427EB8C6` (`request_id`) USING BTREE;
ALTER TABLE `facture` ADD CONSTRAINT `FK_69B3BF29427EB8C6` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `facture` ADD COLUMN `pre_facture_id`  int(11) NULL DEFAULT NULL AFTER `request_id`;
ALTER TABLE `facture` ADD INDEX `IDX_69B3BF29427EB8C7` (`pre_facture_id`) USING BTREE;
ALTER TABLE `facture` ADD CONSTRAINT `FK_69B3BF29427EB8C7` FOREIGN KEY (`pre_facture_id`) REFERENCES `pre_facture` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `request_product` ADD COLUMN `state`  varchar(255) NULL AFTER `is_ariplane_mattress`;
