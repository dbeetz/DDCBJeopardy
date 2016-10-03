# DROP TABLE IF EXISTS score;
# DROP TABLE IF EXISTS gameQna;
# DROP TABLE IF EXISTS badCategoryName;
# DROP TABLE IF EXISTS qna;
# DROP TABLE IF EXISTS category;
# DROP TABLE IF EXISTS player;
# DROP TABLE IF EXISTS game;
# DROP TABLE IF EXISTS attendance;
# DROP TABLE IF EXISTS comment;
# DROP TABLE IF EXISTS student;
# DROP TABLE IF EXISTS alert;
# DROP TABLE IF EXISTS cohort;

CREATE TABLE cohort (
	cohortId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	cohortStartDate DATE NOT NULL,
	PRIMARY KEY(cohortId)
);

CREATE TABLE alert (
	alertId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	alertLevel VARCHAR(16) NOT NULL,
	alertClassName VARCHAR (16) NOT NULL,
	PRIMARY KEY(alertId)
);

CREATE TABLE student (
	studentId CHAR(9) NOT NULL,
	studentCohortId INT UNSIGNED NOT NULL,
	studentLumenClassId INT UNSIGNED,
	studentName VARCHAR(128) NOT NULL,
	studentUsername VARCHAR(20) NOT NULL,
	studentSlackUsername VARCHAR(32),
	INDEX(studentId),
	INDEX(studentCohortId),
	FOREIGN KEY(studentCohortId) REFERENCES cohort(cohortId),
	PRIMARY KEY(studentId, studentCohortId)
);

CREATE TABLE comment (
	commentId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	commentAlertId INT UNSIGNED NOT NULL,
	commentCohortId INT UNSIGNED NOT NULL,
	commentCreateDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	commentStudentId CHAR(9) NOT NULL,
	commentStudentVisible TINYINT UNSIGNED NOT NULL,
	commentText VARCHAR(255) NOT NULL,
	commentUsername VARCHAR(20) NOT NULL,
	INDEX(commentAlertId),
	INDEX(commentCohortId),
	INDEX(commentStudentId),
	FOREIGN KEY(commentAlertId) REFERENCES alert(alertId),
	FOREIGN KEY(commentCohortId) REFERENCES cohort(cohortId),
	FOREIGN KEY(commentStudentId) REFERENCES student(studentId),
	PRIMARY KEY(commentId)
);

CREATE TABLE attendance (
	attendanceId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	attendanceCohortId INT UNSIGNED NOT NULL,
	attendanceStudentId CHAR(9) NOT NULL,
	attendanceDate DATE NOT NULL,
	attendanceCreateDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	attendanceIpAddress VARBINARY(16),
	attendanceBrowser VARCHAR(255),
	attendanceHours DECIMAL(3, 2),
	attendanceOverrideUsername CHAR(20),
	INDEX(attendanceCohortId),
	INDEX(attendanceStudentId),
	INDEX(attendanceDate),
	UNIQUE(attendanceCohortId, attendanceStudentId, attendanceDate),
	FOREIGN KEY(attendanceCohortId) REFERENCES cohort(cohortId),
	FOREIGN KEY(attendanceStudentId) REFERENCES student(studentId),
	PRIMARY KEY(attendanceId)
);

CREATE TABLE game (
	gameId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	gameDailyDoubleId INT UNSIGNED NOT NULL,
	gameDateTime DATETIME NOT NULL,
	gameFinalJeopardyId INT UNSIGNED NOT NULL,
	PRIMARY KEY(gameId)
);

CREATE TABLE player (
	playerId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	playerGameId INT UNSIGNED NOT NULL,
	playerStudentId CHAR(9) NOT NULL,
	playerStudentCohortId INT UNSIGNED NOT NULL,
	INDEX(playerGameId),
	INDEX(playerStudentId, playerStudentCohortId),
	FOREIGN KEY(playerGameId) REFERENCES game(gameId),
	FOREIGN KEY(playerStudentId) REFERENCES student(studentId),
	FOREIGN KEY(playerStudentCohortId) REFERENCES student(studentCohortId),
	PRIMARY KEY(playerId)
);

CREATE TABLE category(
	categoryId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	categoryName VARCHAR(128) NOT NULL,
	PRIMARY KEY(categoryId)
);

CREATE TABLE  qna(
	qnaId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	qnaCategoryId INT UNSIGNED NOT NULL,
	qnaAnswer VARCHAR(256) NOT NULL,
	qnaPointVal INT UNSIGNED NOT NULL,
	qnaQuestion VARCHAR(256) NOT NULL,
	INDEX (qnaCategoryId),
	FOREIGN KEY (qnaCategoryId) REFERENCES category(categoryId),
	PRIMARY KEY (qnaId)
);

CREATE TABLE badCategoryName (
	badCategoryNameCategoryId INT UNSIGNED NOT NULL,
	badCategoryNameGameId INT UNSIGNED NOT NULL,
	badCategoryNameName VARCHAR(128),
	INDEX(badCategoryNameCategoryId),
	INDEX(badCategoryNameGameId),
	FOREIGN KEY(badCategoryNameCategoryId) REFERENCES category(categoryId),
	FOREIGN KEY(badCategoryNameGameId) REFERENCES game(gameId),
	UNIQUE(badCategoryNameName),
	PRIMARY KEY(badCategoryNameCategoryId, badCategoryNameGameId)
);

CREATE TABLE gameQna (
	gameQnaId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	gameQnaGameId INT UNSIGNED NOT NULL,
	gameQnaQnaId INT UNSIGNED NOT NULL,
	INDEX(gameQnaGameId),
	INDEX(gameQnaQnaId),
	FOREIGN KEY(gameQnaGameId) REFERENCES game(gameId),
	FOREIGN KEY(gameQnaQnaId) REFERENCES qna(qnaId),
	PRIMARY KEY(gameQnaId)
);

CREATE TABLE score (
	scoreGameQnaId INT UNSIGNED NOT NULL,
	scorePlayerId INT UNSIGNED NOT NULL,
	scoreFinalJeopardyAnswer VARCHAR(128),
	scoreVal INT SIGNED,
	INDEX(scoreGameQnaId),
	INDEX(scorePlayerId),
	FOREIGN KEY(scoreGameQnaId) REFERENCES gameQna(gameQnaId),
	FOREIGN KEY(scorePlayerId) REFERENCES player(playerId),
	PRIMARY KEY(scoreGameQnaId, scorePlayerId)
);
