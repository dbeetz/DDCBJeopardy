# DROP TABLE IF EXISTS finalJeopardy;
# DROP TABLE IF EXISTS gameQna;
# DROP TABLE IF EXISTS badCategoryName;
# DROP TABLE IF EXISTS qna;
# DROP TABLE IF EXISTS category;
# DROP TABLE IF EXISTS score;
# DROP TABLE IF EXISTS game;
# DROP TABLE IF EXISTS comment;
# DROP TABLE IF EXISTS alert;
# DROP TABLE IF EXISTS student;
# DROP TABLE IF EXISTS cohort;
# DROP TABLE IF EXISTS attendance;


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
	gameStudentId CHAR(9) NOT NULL,
	gameDailyDoubleId INT UNSIGNED NOT NULL,
	gameDateTime DATETIME NOT NULL,
	gameFinalJeopardyId INT UNSIGNED NOT NULL,
	INDEX(gameStudentId),
	FOREIGN KEY(gameStudentId) REFERENCES student(studentId),
	PRIMARY KEY(gameId)
);

CREATE TABLE score (
	scoreId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	scoreGameId INT UNSIGNED,
	scoreStudentId CHAR(9) NOT NULL,
	scoreStudentScore INT SIGNED,
	INDEX(scoreGameId),
	INDEX(scoreStudentId),
	FOREIGN KEY(scoreGameId) REFERENCES game(gameId),
	FOREIGN KEY(scoreStudentId) REFERENCES student(studentId),
	PRIMARY KEY(scoreId)
);

CREATE TABLE category(
	categoryId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	categoryGameId INT UNSIGNED,
	categoryName VARCHAR(128) NOT NULL,
	INDEX(categoryGameId),
	FOREIGN KEY (categoryGameId) REFERENCES game(gameId),
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
	gameQnaGameId INT UNSIGNED NOT NULL,
	gameQnaQnaId INT UNSIGNED NOT NULL,
	INDEX(gameQnaGameId),
	INDEX(gameQnaQnaId),
	FOREIGN KEY(gameQnaGameId) REFERENCES game(gameId),
	FOREIGN KEY(gameQnaQnaId) REFERENCES qna(qnaId),
	PRIMARY KEY(gameQnaGameId, gameQnaQnaId)
);

CREATE TABLE finalJeopardy (
	finalJeopardyId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	finalJeopardyGameId INT UNSIGNED NOT NULL,
	finalJeopardyStudentId CHAR(9) NOT NULL,
	finalJeopardyQnaId INT UNSIGNED NOT NULL,
	finalJeopardyAnswer VARCHAR(256) NOT NULL,
	finalJeopardyWager INT UNSIGNED NOT NULL,
	INDEX(finalJeopardyGameId),
	INDEX(finalJeopardyStudentId),
	INDEX(finalJeopardyQnaId),
	FOREIGN KEY(finalJeopardyGameId) REFERENCES game(gameId),
	FOREIGN KEY(finalJeopardyStudentId) REFERENCES student(studentId),
	FOREIGN KEY(finalJeopardyQnaId) REFERENCES qna(qnaId),
	PRIMARY KEY(finalJeopardyId)
);