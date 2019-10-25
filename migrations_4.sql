ALTER TABLE `product` ADD COLUMN `priority` int(11) NOT NULL DEFAULT 0 AFTER `is_ariplane_mattress`;

ALTER TABLE `member` ADD COLUMN `balance` int(11) NOT NULL DEFAULT 0 AFTER `address`;

CREATE TABLE `balance_update` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `date`  datetime NOT NULL ,
  `balance`  int(11) NOT NULL ,
  `member_id`  int(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_4B978F9F19EB6923` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX `IDX_4B978F9F19EB6923` (`member_id`) USING BTREE
);
