CREATE TABLE  `bsc`.`jos_boxes` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`box_title` VARCHAR( 255 ) NOT NULL ,
`address_line1` VARCHAR( 255 ) NOT NULL ,
`address_line2` VARCHAR( 255 ) NOT NULL ,
`city` VARCHAR( 255 ) NOT NULL ,
`state` VARCHAR( 255 ) NOT NULL ,
`zip` VARCHAR( 255 ) NOT NULL ,
`country` VARCHAR( 255 ) NOT NULL ,
`user_id` INT NOT NULL ,
`created_at` DATETIME NOT NULL
) ENGINE = MYISAM ;
