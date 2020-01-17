CREATE TABLE `teacher_lessons` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255),
    `sub_title` VARCHAR(255),
    `icon` VARCHAR(255),
    `is_recommended` SMALLINT DEFAULT 0,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB;

CREATE TABLE `teacher_lessons_category` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    PRIMARY KEY(`id`)
) ENGINE = InnoDB;

CREATE TABLE `teacher_lessons_lesson_category` (
    `lesson_id` INT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    FOREIGN KEY(`lesson_id`) REFERENCES `teacher_lessons`(`id`),
    FOREIGN KEY(`category_id`) REFERENCES `teacher_lessons_category`(`id`),
    PRIMARY KEY(`lesson_id`, `category_id`)
) ENGINE = InnoDB;
