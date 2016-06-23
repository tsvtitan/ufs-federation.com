DROP TABLE DIRS;

CREATE TABLE DIRS
(
  DIR_ID NVARCHAR2(32) NOT NULL,
  LOCATION NVARCHAR2(1000) NOT NULL,
  DESCRIPTION NVARCHAR2(250),
  PRIMARY KEY (DIR_ID),
  FOREIGN KEY (DIR_ID) REFERENCES PATHS (PATH_ID)
)