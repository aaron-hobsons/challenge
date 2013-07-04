SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `hobsons` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `hobsons` ;

-- -----------------------------------------------------
-- Table `hobsons`.`gender`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`gender` (
  `gender_id` INT NOT NULL ,
  `gender` VARCHAR(20) NULL ,
  PRIMARY KEY (`gender_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hobsons`.`title`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`title` (
  `title_id` VARCHAR(10) NOT NULL ,
  `description` VARCHAR(255) NULL ,
  PRIMARY KEY (`title_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hobsons`.`person`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`person` (
  `person_id` INT NOT NULL AUTO_INCREMENT ,
  `title_id` VARCHAR(10) NOT NULL ,
  `first_name` VARCHAR(50) NULL ,
  `last_name` VARCHAR(50) NULL ,
  `birth_date` DATE NULL ,
  `gender_id` INT NOT NULL ,
  PRIMARY KEY (`person_id`) ,
  INDEX `fk_person_gender1_idx` (`gender_id` ASC) ,
  INDEX `fk_person_title1_idx` (`title_id` ASC) ,
  CONSTRAINT `fk_person_gender1`
    FOREIGN KEY (`gender_id` )
    REFERENCES `hobsons`.`gender` (`gender_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_title1`
    FOREIGN KEY (`title_id` )
    REFERENCES `hobsons`.`title` (`title_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hobsons`.`student`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`student` (
  `student_id` VARCHAR(20) NOT NULL ,
  `person_id` INT NOT NULL ,
  PRIMARY KEY (`student_id`) ,
  INDEX `fk_student_person_idx` (`person_id` ASC) ,
  CONSTRAINT `fk_student_person`
    FOREIGN KEY (`person_id` )
    REFERENCES `hobsons`.`person` (`person_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hobsons`.`teacher`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`teacher` (
  `teacher_id` INT NOT NULL AUTO_INCREMENT ,
  `person_id` INT NOT NULL ,
  PRIMARY KEY (`teacher_id`) ,
  INDEX `fk_teacher_person1_idx` (`person_id` ASC) ,
  CONSTRAINT `fk_teacher_person1`
    FOREIGN KEY (`person_id` )
    REFERENCES `hobsons`.`person` (`person_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hobsons`.`course`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`course` (
  `course_id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(50) NULL ,
  PRIMARY KEY (`course_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hobsons`.`section`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`section` (
  `section_id` INT NOT NULL ,
  `class_id` INT NOT NULL ,
  PRIMARY KEY (`section_id`) ,
  INDEX `fk_section_class1_idx` (`class_id` ASC) ,
  CONSTRAINT `fk_section_class1`
    FOREIGN KEY (`class_id` )
    REFERENCES `hobsons`.`course` (`course_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hobsons`.`student_section`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`student_section` (
  `student_id` VARCHAR(20) NOT NULL ,
  `section_id` INT NOT NULL ,
  PRIMARY KEY (`student_id`, `section_id`) ,
  INDEX `fk_student_section_section1_idx` (`section_id` ASC) ,
  INDEX `fk_student_section_student1_idx` (`student_id` ASC) ,
  CONSTRAINT `fk_student_section_student1`
    FOREIGN KEY (`student_id` )
    REFERENCES `hobsons`.`student` (`student_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_section_section1`
    FOREIGN KEY (`section_id` )
    REFERENCES `hobsons`.`section` (`section_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hobsons`.`teacher_section`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hobsons`.`teacher_section` (
  `teacher_id` INT NOT NULL ,
  `section_id` INT NOT NULL ,
  PRIMARY KEY (`teacher_id`, `section_id`) ,
  INDEX `fk_teacher_section_section1_idx` (`section_id` ASC) ,
  INDEX `fk_teacher_section_teacher1_idx` (`teacher_id` ASC) ,
  CONSTRAINT `fk_teacher_section_teacher1`
    FOREIGN KEY (`teacher_id` )
    REFERENCES `hobsons`.`teacher` (`teacher_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_teacher_section_section1`
    FOREIGN KEY (`section_id` )
    REFERENCES `hobsons`.`section` (`section_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
