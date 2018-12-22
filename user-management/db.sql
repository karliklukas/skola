-- MySQL Workbench Forward Engineering


-- -----------------------------------------------------
-- Schema staveb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema staveb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `staveb` DEFAULT CHARACTER SET utf8 ;
USE `staveb` ;

-- -----------------------------------------------------
-- Table `staveb`.`uzivatel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `staveb`.`uzivatel` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `jmeno` VARCHAR(45) NOT NULL,
  `prijmeni` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `heslo` VARCHAR(50) NOT NULL,
  `opravneni` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `staveb`.`faktury`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `staveb`.`faktury` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `datum_vytvoreni` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datum_vydani` DATETIME NOT NULL,
  `uzivatel_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_faktury_uzivatel_idx` (`uzivatel_id` ASC),
  CONSTRAINT `fk_faktury_uzivatel`
    FOREIGN KEY (`uzivatel_id`)
    REFERENCES `staveb`.`uzivatel` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `staveb`.`vyrobce`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `staveb`.`vyrobce` (
  `idvyrobce` INT NOT NULL AUTO_INCREMENT,
  `nazev` VARCHAR(45) NOT NULL,
  `mesto` VARCHAR(45) NOT NULL,
  `adresa` VARCHAR(45) NOT NULL,
  `ico` INT NOT NULL,
  PRIMARY KEY (`idvyrobce`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `staveb`.`kategorie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `staveb`.`kategorie` (
  `idkategorie` INT NOT NULL AUTO_INCREMENT,
  `nazev` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idkategorie`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `staveb`.`zbozi`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `staveb`.`zbozi` (
  `idzbozi` INT NOT NULL AUTO_INCREMENT,
  `nazev` VARCHAR(45) NOT NULL,
  `popis` VARCHAR(45) NOT NULL,
  `mnozstvi` INT NOT NULL,
  `vyrobce_id` INT NOT NULL,
  `kategorie_id` INT NOT NULL,
  PRIMARY KEY (`idzbozi`),
  INDEX `fk_zbozi_vyrobce1_idx` (`vyrobce_id` ASC),
  INDEX `fk_zbozi_kategorie1_idx` (`kategorie_id` ASC),
  CONSTRAINT `fk_zbozi_vyrobce1`
    FOREIGN KEY (`vyrobce_id`)
    REFERENCES `staveb`.`vyrobce` (`idvyrobce`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_zbozi_kategorie1`
    FOREIGN KEY (`kategorie_id`)
    REFERENCES `staveb`.`kategorie` (`idkategorie`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `staveb`.`cena`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `staveb`.`cena` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cena` INT NOT NULL,
  `datum` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `zbozi_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_cena_zbozi1_idx` (`zbozi_id` ASC),
  CONSTRAINT `fk_cena_zbozi1`
    FOREIGN KEY (`zbozi_id`)
    REFERENCES `staveb`.`zbozi` (`idzbozi`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `staveb`.`objednavky`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `staveb`.`objednavky` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `mnozstvi` INT NOT NULL,
  `faktury_id` INT NOT NULL,
  `cena_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_objednavky_faktury1_idx` (`faktury_id` ASC),
  INDEX `fk_objednavky_cena1_idx` (`cena_id` ASC),
  CONSTRAINT `fk_objednavky_faktury1`
    FOREIGN KEY (`faktury_id`)
    REFERENCES `staveb`.`faktury` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_objednavky_cena1`
    FOREIGN KEY (`cena_id`)
    REFERENCES `staveb`.`cena` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


