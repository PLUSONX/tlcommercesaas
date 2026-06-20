-- Optional: run on tenant DB if quiz_features does not yet have slug column
ALTER TABLE `quiz_features`
    ADD COLUMN `slug` VARCHAR(255) NULL AFTER `title`,
    ADD UNIQUE KEY `uq_quiz_features_slug` (`slug`);
