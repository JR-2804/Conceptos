ALTER TABLE `request` ADD COLUMN `two_step_extra` double NOT NULL AFTER `first_client_discount`;
ALTER TABLE `request` ADD COLUMN `cuc_extra` double NOT NULL AFTER `two_step_extra`;

ALTER TABLE `facture` ADD COLUMN `two_step_extra` double NOT NULL AFTER `pre_facture_id`;
ALTER TABLE `facture` ADD COLUMN `cuc_extra` double NOT NULL AFTER `two_step_extra`;

ALTER TABLE `pre_facture` ADD COLUMN `two_step_extra` double NOT NULL AFTER `request_id`;
ALTER TABLE `pre_facture` ADD COLUMN `cuc_extra` double NOT NULL AFTER `two_step_extra`;

ALTER TABLE `member` ADD COLUMN `date`  date NOT NULL AFTER `balance`;
