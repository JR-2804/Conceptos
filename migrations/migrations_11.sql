ALTER TABLE `client`
CHANGE COLUMN `name` `first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `id`,
ADD COLUMN `last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `first_name`;
