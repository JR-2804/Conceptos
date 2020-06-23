ALTER TABLE `request` ADD COLUMN `bags_extra` double NOT NULL AFTER `balance_discount`;
ALTER TABLE `facture` ADD COLUMN `bags_extra` double NOT NULL AFTER `cuc_extra`;
ALTER TABLE `pre_facture` ADD COLUMN `bags_extra` double NOT NULL AFTER `cuc_extra`;

CREATE TABLE `shop_cart_bags` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `number_of_payed_bags`  int(11) NOT NULL ,
  `number_of_free_bags`  int(11) NOT NULL ,
  `user_id`  int(11) NOT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_944A64797597D4F0` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX `IDX_944A64797597D4F0` (`user_id`) USING BTREE
);

ALTER TABLE `facture` ADD COLUMN `commercial_id`  int(11) NULL DEFAULT NULL AFTER `bags_extra`, ADD INDEX `IDX_3B978F9F19EB6955` (`commercial_id`) USING BTREE;
ALTER TABLE `facture` ADD CONSTRAINT `FK_3B978F9F19EB6955` FOREIGN KEY (`commercial_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `pre_facture` ADD COLUMN `commercial_id`  int(11) NULL DEFAULT NULL AFTER `bags_extra`, ADD INDEX `IDX_3B978F9F19ED6955` (`commercial_id`) USING BTREE;
ALTER TABLE `pre_facture` ADD CONSTRAINT `FK_3B978F9F19ED6955` FOREIGN KEY (`commercial_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
