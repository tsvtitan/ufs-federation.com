DROP TABLE SYSTEMS;

CREATE TABLE SYSTEMS
(
  SYSTEM_ID NVARCHAR2(32) NOT NULL,
  NAME NVARCHAR2(100) NOT NULL,
  DESCRIPTION NVARCHAR2(250),
  DB_LINK VARCHAR2(100),
  PRIMARY KEY (SYSTEM_ID)
)