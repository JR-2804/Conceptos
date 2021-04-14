ALTER TABLE `request` ADD COLUMN `logistic_cost`  double NOT NULL DEFAULT 0 AFTER `bags_extra`;
ALTER TABLE `request` ADD COLUMN `security_cost`  double NOT NULL DEFAULT 0 AFTER `logistic_cost`;
ALTER TABLE `request` ADD COLUMN `taxes`  double NOT NULL DEFAULT 0 AFTER `security_cost`;

ALTER TABLE `facture` ADD COLUMN `logistic_cost`  double NOT NULL DEFAULT 0 AFTER `commercial_id`;
ALTER TABLE `facture` ADD COLUMN `security_cost`  double NOT NULL DEFAULT 0 AFTER `logistic_cost`;
ALTER TABLE `facture` ADD COLUMN `taxes`  double NOT NULL DEFAULT 0 AFTER `security_cost`;

ALTER TABLE `pre_facture` ADD COLUMN `logistic_cost`  double NOT NULL DEFAULT 0 AFTER `commercial_id`;
ALTER TABLE `pre_facture` ADD COLUMN `security_cost`  double NOT NULL DEFAULT 0 AFTER `logistic_cost`;
ALTER TABLE `pre_facture` ADD COLUMN `taxes`  double NOT NULL DEFAULT 0 AFTER `security_cost`;
