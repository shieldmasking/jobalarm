ALTER TABLE `platform_admins`
  ADD COLUMN `reset_token` VARCHAR(64) DEFAULT NULL,
  ADD COLUMN `reset_expires` DATETIME DEFAULT NULL;
