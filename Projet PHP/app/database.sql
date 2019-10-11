CREATE TABLE IF NOT EXISTS `users` (
	`id` int(10) AUTO_INCREMENT,
	`username` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`password` varchar(256) NOT NULL,
	`secret_question` varchar(256) NOT NULL,
	`secret_question_answer` varchar(256) NOT NULL,
	`profile_image` varchar(150) NOT NULL,
	`registration_date` varchar(100) NOT NULL,
	`old_login_date` varchar(100) NOT NULL,
	`donations_value` int(10) NOT NULL,
	`newpassword_key` varchar(255) DEFAULT 'N',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `users_real_passwords` (
	`id` int(10) NOT NULL,
	`password` varchar(256) NOT NULL,
	PRIMARY KEY(`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `users_notifications` (
	`id` int(10) AUTO_INCREMENT,
	`type` int(2) NOT NULL,
	`user_get` int(10) NOT NULL,
	`user_send` int(10) NOT NULL,
	`send_date` date NOT NULL,
	`read` int(1) NOT NULL,
	PRIMARY KEY(`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `essay_categories` (
	`id` int(10) AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	PRIMARY KEY(`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `essay_tags` (
	`id` int(10) AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	PRIMARY KEY(`id`)
) ENGINE=InnoDB;