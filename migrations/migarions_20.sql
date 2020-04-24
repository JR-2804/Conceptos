ALTER TABLE `request` ADD COLUMN `combo_discount` double NULL AFTER `first_client_discount`;
ALTER TABLE `pre_facture` ADD COLUMN `combo_discount` double NULL AFTER `first_client_discount`;
ALTER TABLE `facture` ADD COLUMN `combo_discount` double NULL AFTER `first_client_discount`;
