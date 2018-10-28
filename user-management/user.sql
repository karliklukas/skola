CREATE TABLE `db_dev`.`user` ( 
    `id` INT NOT NULL AUTO_INCREMENT ,
    `username` TEXT NOT NULL ,
    `password` TEXT NOT NULL ,
    `email` TEXT NOT NULL ,
    `description` TEXT NOT NULL ,
    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;