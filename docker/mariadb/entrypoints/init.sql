CREATE DATABASE IF NOT EXISTS `notes`;
CREATE DATABASE IF NOT EXISTS `notes_test`;

CREATE USER IF NOT EXISTS 'notes'@'%' IDENTIFIED BY 'notes';

GRANT ALL PRIVILEGES ON `notes`.* TO 'notes'@'%';
GRANT ALL PRIVILEGES ON `notes_test`.* TO 'notes'@'%';

-- GRANT ALL ON `notes_test`.* TO 'notes_test'@'%' IDENTIFIED BY 'notes_test';

GRANT SELECT ON `information\_schema`.* TO 'notes'@'%';

FLUSH PRIVILEGES;

SET GLOBAL time_zone = 'UTC';
