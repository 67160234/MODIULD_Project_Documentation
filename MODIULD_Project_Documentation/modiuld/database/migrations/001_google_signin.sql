-- Run this once for an existing database. New installations use init.sql.
ALTER TABLE `users`
    MODIFY `password_hash` VARCHAR(255) DEFAULT NULL,
    ADD COLUMN `google_sub` VARCHAR(255) DEFAULT NULL AFTER `password_hash`,
    ADD UNIQUE KEY `uq_google_sub` (`google_sub`);
