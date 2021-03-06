CREATE TABLE `school`.`users` ( `no.` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , `admission` INT(10) NOT NULL , `email` VARCHAR(100) NOT NULL , `password` VARCHAR(128) NOT NULL , `phone` INT(15) NOT NULL , `datecreated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`no.`)) ENGINE = InnoDB; 
ALTER TABLE `users` ADD `DOB` DATE NOT NULL AFTER `email`;
ALTER TABLE `users` ADD `profilepic` VARCHAR(255) NOT NULL AFTER `phone`;
CREATE TABLE `school`.`authenticate` ( `no.` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(100) NOT NULL , `password` VARCHAR(128) NOT NULL , PRIMARY KEY (`no.`), UNIQUE (`username`)) ENGINE = InnoDB; 
CREATE TABLE `school`.`posttexts` ( `no.` INT NOT NULL AUTO_INCREMENT , `postedBy` VARCHAR(100) NOT NULL , `postedFor` VARCHAR(100) NOT NULL , `title` VARCHAR(1000) NOT NULL , `description` VARCHAR(10000) NOT NULL , `timePosted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`no.`)) ENGINE = InnoDB;  
ALTER TABLE `posttexts` ADD `id` VARCHAR(50) NOT NULL AFTER `no.`, ADD UNIQUE `id` (`id`);  
ALTER TABLE `posttexts` ADD `mediaIncluded` BOOLEAN NOT NULL AFTER `description`; 