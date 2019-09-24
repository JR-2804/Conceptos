-- ALTER TABLE `request` ADD COLUMN `first_client_discount`  double NULL AFTER `discount`;

-- CREATE TABLE `evaluation` (
--   `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ,
--   `evaluation_value`  double NOT NULL ,
--    PRIMARY KEY (`id`)
-- );

-- ALTER TABLE `product` ADD COLUMN `evaluation_id`  int(11) NULL DEFAULT NULL AFTER `is_lamp`;
-- ALTER TABLE `product` ADD INDEX `IDX_D34A04AD12469DE1` (`evaluation_id`) USING BTREE ;
-- ALTER TABLE `product` ADD CONSTRAINT `FK_D34A04AD12469DE1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- CREATE TABLE `product_evaluation` (
--   `product_id`  int(11) NOT NULL ,
--   `evaluation_id`  int(11) NOT NULL ,
--   PRIMARY KEY (`product_id`, `evaluation_id`),
--   CONSTRAINT `FK_CDFC735612469DE1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
--   CONSTRAINT `FK_CDFC73564584665B` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
--   INDEX `IDX_CDFC73564584665B` (`product_id`) USING BTREE ,
--   INDEX `IDX_CDFC735612469DE1` (`evaluation_id`) USING BTREE
-- );

-- ALTER TABLE `evaluation` ADD COLUMN `user_id`  int(11) NULL AFTER `id`;
-- ALTER TABLE `evaluation` ADD INDEX `IDX_8E1EAAC37597D3FF` (`user_id`) USING BTREE;
-- ALTER TABLE `evaluation` ADD CONSTRAINT `FK_8E1EAAC37597D3FF` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

-- Remove product_evaluation table
