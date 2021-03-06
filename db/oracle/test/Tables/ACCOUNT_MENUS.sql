DROP TABLE ACCOUNT_MENUS;

CREATE TABLE ACCOUNT_MENUS
(
  ACCOUNT_ID NVARCHAR2(32) NOT NULL,
  MENU_ID NVARCHAR2(32) NOT NULL,
  PRIORITY INTEGER,
  PRIMARY KEY (ACCOUNT_ID,MENU_ID),
  FOREIGN KEY (ACCOUNT_ID) REFERENCES ACCOUNTS (ACCOUNT_ID),
  FOREIGN KEY (MENU_ID) REFERENCES MENUS (MENU_ID)
)