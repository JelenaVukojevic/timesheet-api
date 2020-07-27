CREATE DATABASE timesheet
  CHARACTER SET utf8
  COLLATE utf8_general_ci;

CREATE TABLE `dates` ( 
  `id` INT NOT NULL AUTO_INCREMENT , 
  `date` DATE NOT NULL , 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `items` ( 
  `id` INT NOT NULL AUTO_INCREMENT , 
  `date_id` INT NOT NULL , 
  `title` VARCHAR(250) NOT NULL , 
  `hours` FLOAT NOT NULL , 
  `created_at` TIMESTAMP NOT NULL DEFAULT NOW() , 
  `updated_at` TIMESTAMP NULL DEFAULT NULL  ON UPDATE NOW() , 
  PRIMARY KEY (`id`) ,
  FOREIGN KEY (`date_id`) REFERENCES `dates`(`id`)
) ENGINE = InnoDB;