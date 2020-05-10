CREATE TABLE `external_request` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `final_price`  double NOT NULL ,
  `weight`  double NOT NULL ,
  `budget`  double NOT NULL ,
  `payment`  double NOT NULL ,
  `date`  datetime NOT NULL ,
  `state`  varchar(255) NOT NULL ,
  `user_id`  int(11) ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_3B989F9F19EB6921` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX `IDX_3B989F9F19EB6921` (`user_id`) USING BTREE
);

CREATE TABLE `external_request_product` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `product_id`  int(11) ,
  `external_request_id`  int(11) NOT NULL ,
  `count`  int(11) NOT NULL ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_F97E86FA4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `FK_F97E86FA427EB8A5` FOREIGN KEY (`external_request_id`) REFERENCES `external_request` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  INDEX `IDX_F97E86FA4584665A` (`product_id`) USING BTREE ,
  INDEX `IDX_F97E86FA427EB8A5` (`external_request_id`) USING BTREE
);
