CREATE TABLE IF NOT EXISTS game_users (
	char_id INT PRIMARY KEY AUTO_INCREMENT,
	username  VARCHAR(16) NOT NULL UNIQUE,
	email     VARCHAR(48) NOT NULL UNIQUE,
	pass_word VARCHAR(32) NOT NULL,
	char_rank CHAR(1)     NOT NULL DEFAULT 'E'
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS char_attributes (
	char_id INT PRIMARY KEY,
	char_level       INT NOT NULL DEFAULT 1,
	energy           INT NOT NULL DEFAULT 100,
	flair            INT NOT NULL DEFAULT 1,
	strength         INT NOT NULL DEFAULT 1,
	agility          INT NOT NULL DEFAULT 1,
	jutsu            INT NOT NULL DEFAULT 1,
	tactics          INT NOT NULL DEFAULT 1,
	attribute_points INT NOT NULL DEFAULT 50,
	points_needed    INT NOT NULL DEFAULT 6
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS style_attributes (
	char_id INT PRIMARY KEY,
	style_name   VARCHAR(8)   DEFAULT '',
	kenjutsu     INT NOT NULL DEFAULT 1,
	shuriken     INT NOT NULL DEFAULT 1,
	taijutsu     INT NOT NULL DEFAULT 1,
	ninjutsu     INT NOT NULL DEFAULT 1,
	genjutsu     INT NOT NULL DEFAULT 1,
	skill_points INT NOT NULL DEFAULT 50
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS skill_training (
	char_id INT PRIMARY KEY,
	kenjutsu_points      INT        NOT NULL DEFAULT 0,
	shuriken_points      INT        NOT NULL DEFAULT 0,
	taijutsu_points      INT        NOT NULL DEFAULT 0,
	ninjutsu_points      INT        NOT NULL DEFAULT 0,
	genjutsu_points      INT        NOT NULL DEFAULT 0,
	skill_training       VARCHAR(8) NOT NULL DEFAULT '',
	sessions_in_training INT        NOT NULL DEFAULT 0,
	time_ready           DATETIME   NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS stats (
	char_id INT PRIMARY KEY,
	wins   INT NOT NULL DEFAULT 0,
	patrol INT NOT NULL DEFAULT 0,
	anbu   INT NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS char_team (
	char_id INT PRIMARY KEY,
	teammate1_id    INT NOT NULL DEFAULT 0,
	teammate2_id    INT NOT NULL DEFAULT 0,
	team_exam_phase INT NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS mail (
	msg_id INT PRIMARY KEY AUTO_INCREMENT,
	msg_time    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	sender_id   INT  NOT NULL,
	receiver_id INT  NOT NULL,
	msg_text    TEXT NOT NULL
) ENGINE = InnoDB;