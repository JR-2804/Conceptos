ALTER TABLE `evaluation`
ADD COLUMN `general_opinion`  varchar(255) NOT NULL AFTER `evaluation_value`,
ADD COLUMN `price_quality_value`  double NOT NULL AFTER `general_opinion`,
ADD COLUMN `utility_value`  double NOT NULL AFTER `price_quality_value`,
ADD COLUMN `durability_value`  double NOT NULL AFTER `utility_value`,
ADD COLUMN `quality_value`  double NOT NULL AFTER `durability_value`,
ADD COLUMN `design_value`  double NOT NULL AFTER `quality_value`,
ADD COLUMN `comment`  varchar(255) NOT NULL AFTER `design_value`,
ADD COLUMN `is_recommended`  tinyint(1) NOT NULL AFTER `comment`;
