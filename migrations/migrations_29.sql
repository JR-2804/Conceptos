ALTER TABLE `external_request`
  ADD COLUMN `ticket_price`  double NOT NULL AFTER `user_id`,
  ADD COLUMN `provider_profit`  double NOT NULL AFTER `ticket_price`,
  ADD COLUMN `sell_price`  double NOT NULL AFTER `provider_profit`;
