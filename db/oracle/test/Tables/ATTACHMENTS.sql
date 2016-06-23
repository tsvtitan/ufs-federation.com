DROP TABLE ATTACHMENTS;

CREATE TABLE ATTACHMENTS
(
  ATTACHMENT_ID NVARCHAR2(32) NOT NULL,
  MESSAGE_ID NVARCHAR2(32) NOT NULL,
  NAME NVARCHAR2(250),
  EXTENSION NVARCHAR2(100),
  DATA BLOB,
  CONTENT_TYPE NVARCHAR2(100),
  CONTENT_ID NVARCHAR2(100),
  PRIMARY KEY (ATTACHMENT_ID),
  FOREIGN KEY (MESSAGE_ID) REFERENCES MESSAGES (MESSAGE_ID)
)