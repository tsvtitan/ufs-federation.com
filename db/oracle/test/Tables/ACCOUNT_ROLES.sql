DROP TABLE ACCOUNT_ROLES;

CREATE TABLE ACCOUNT_ROLES
(
  ACCOUNT_ID NVARCHAR2(32) NOT NULL,
  ROLE_ID NVARCHAR2(32) NOT NULL,
  PRIMARY KEY (ACCOUNT_ID,ROLE_iD),
  FOREIGN KEY (ACCOUNT_ID) REFERENCES ACCOUNTS (ACCOUNT_ID),
  FOREIGN KEY (ROLE_ID) REFERENCES ACCOUNTS (ACCOUNT_ID)
)