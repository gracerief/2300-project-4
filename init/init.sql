BEGIN TRANSACTION;

CREATE TABLE events (
  'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  'accounts_id' TEXT,
  'event' TEXT NOT NULL UNIQUE,
  'day' TEXT NOT NULL,
  'times' TEXT NOT NULL
);

INSERT INTO events (accounts_id, event, day, times) VALUES ('1', 'Orientation Week Concert', '2018-01-25', '7:00');
INSERT INTO events (accounts_id, event, day, times) VALUES ('2', 'Mid-Semester Ukulele Fun', '2018-03-23', '7:00');
INSERT INTO events (accounts_id, event, day, times) VALUES ('1', 'End of Year Concert', '2018-05-14', '7:00');

CREATE TABLE practices (
  'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  'accounts_id' TEXT,
  'practice' TEXT NOT NULL,
  'day' TEXT NOT NULL,
  'times' TEXT NOT NULL
);

INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'January 23, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('2', 'Practice', 'January 30, 2018', '9:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'February 1, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'February 6, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'February 8, 2018', '9:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'February 13, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'February 15, 2018', '10:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'February 20, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'February 27, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'March 1, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'March 6, 2018', '10:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'March 8, 2018', '8:30 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'March 13, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'March 20, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'March 22, 2018', '9:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'March 27, 2018', '10:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'March 29, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'April 10, 2018', '7:30 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'April 12, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'April 17, 2018', '8:30 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'April 19, 2018', '8:15 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'April 24, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'April 26, 2018', '10:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'May 1, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'May 3, 2018', '9:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'May 8, 2018', '8:00 PM');
INSERT INTO practices (accounts_id, practice, day, times) VALUES ('1', 'Practice', 'May 10, 2018', '10:00 PM');


CREATE TABLE sheets (
  'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  'filename' TEXT NOT NULL,
  'file_ext' TEXT NOT NULL,
  'title' TEXT NOT NULL,
  'artist' TEXT,
  'source' TEXT NOT NULL
);

INSERT INTO `sheets` (id, filename, file_ext, title, artist, source) VALUES (1,'1.pdf', 'pdf', 'Yellow', 'Coldplay', 'liveukulele.com');
INSERT INTO `sheets` (id, filename, file_ext, title, artist, source) VALUES (2,'2.pdf','pdf', 'Thriller', 'Rod Temperton & Michael Jackson', 'liveukulele.com');
INSERT INTO `sheets` (id, filename, file_ext, title, artist, source) VALUES (3,'3.pdf','pdf', '...Baby One More Time', 'Britney Spears', 'liveukulele.com');
INSERT INTO `sheets` (id, filename, file_ext, title, source) VALUES (4,'4.pdf','pdf', 'The Office: An American Workplace Theme', 'liveukulele.com');
INSERT INTO `sheets` (id, filename, file_ext, title, source) VALUES (5,'5.pdf', 'pdf','Jeopardy Theme', 'liveukulele.com');


CREATE TABLE accounts(
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  admin_status TEXT NOT NULL
);
/*passwords are silver and mammoth*/
INSERT INTO accounts (username, password, admin_status) VALUES ('silver','$2y$10$B.tNq34vPfOk/QdUQisFdOGOTqFSxbj4K1kDNAFoLJi20HOAzU3qO','yes'); /*password: mammoth*/
INSERT INTO accounts (username, password, admin_status) VALUES ('mammoth','$2y$10$V1a63VtdZuCefvUkKbNvxelEqzIFdbIm9AhmcA.TA635WmbQHtjBu','no'); /*password: silver*/

CREATE TABLE account_sheets (
  'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  'account_name' TEXT NOT NULL,
  'sheet_id' INTEGER NOT NULL
);

INSERT INTO `account_sheets` (id, account_name, sheet_id) VALUES (1, 'silver', 1);
INSERT INTO `account_sheets` (id, account_name, sheet_id) VALUES (2, 'silver', 4);
INSERT INTO `account_sheets` (id, account_name, sheet_id) VALUES (3, 'mammoth', 1);
INSERT INTO `account_sheets` (id, account_name, sheet_id) VALUES (4, 'mammoth', 3);

CREATE TABLE account_events (
  'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  'account_name' TEXT NOT NULL,
  'practice_id' INTEGER NOT NULL
);

INSERT INTO `account_events` (id, account_name, practice_id) VALUES (1, 'silver', 1);
INSERT INTO `account_events` (id, account_name, practice_id) VALUES (2, 'silver', 4);
INSERT INTO `account_events` (id, account_name, practice_id) VALUES (3, 'mammoth', 1);
INSERT INTO `account_events` (id, account_name, practice_id) VALUES (4, 'mammoth', 3);

COMMIT;
