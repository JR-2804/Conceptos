CREATE TABLE `shop_cart_product` (
  `id`  int(11) NOT NULL AUTO_INCREMENT ,
  `count`  int(11) NOT NULL ,
  `uuid`  varchar(255) NOT NULL ,
  `product_id`  varchar(255) NOT NULL ,
  `user_id`  int(11) ,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_957A64797597D4F0` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX `IDX_957A64797597D4F0` (`user_id`) USING BTREE
);
