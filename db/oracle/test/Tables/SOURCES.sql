DROP TABLE SOURCES;

CREATE TABLE SOURCES
(
  SOURCE_ID NVARCHAR2(32) NOT NULL,
  NAME NVARCHAR2(100) NOT NULL,
  LINK NVARCHAR2(500),
  IMAGE_ID NVARCHAR2(32),
  PRIMARY KEY (SOURCE_ID),
  FOREIGN KEY (IMAGE_ID) REFERENCES PATHS (PATH_ID)
)