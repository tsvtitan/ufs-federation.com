DROP TABLE CLIENTS;

CREATE TABLE CLIENTS
(
  CLIENT_ID NVARCHAR2(32) NOT NULL,
  SPECTRE_UFS_ID INTEGER,
  PRIMARY KEY (CLIENT_ID),
  FOREIGN KEY (CLIENT_ID) REFERENCES ACCOUNTS (ACCOUNT_ID)
)