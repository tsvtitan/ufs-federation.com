DROP TABLE MOBILE_MENUS;

CREATE TABLE MOBILE_MENUS
(
  MOBILE_MENU_ID INTEGER NOT NULL,
  PARENT_ID INTEGER,
  LANG_ID CHAR(2) NOT NULL,
  LOCKED TIMESTAMP,
  NAME NVARCHAR2(100) NOT NULL,
  DESCRIPTION NVARCHAR2(1000),
  MENU_TYPE INTEGER NOT NULL,
  PRIORITY INTEGER,
  DEFAULT_IMAGE BLOB,
  HIGHLIGHT_IMAGE BLOB,
  NEWS_SQL NCLOB,
  NEWS_ALL_COUNT_SQL NCLOB,
  NEWS_ACTUAL_COUNT_SQL NCLOB,
  FILES_SQL NCLOB,
  GROUPS_SQL NCLOB,
  LINKS_SQL NCLOB,
  KEYWORDS_SQL NCLOB,
  HTML NCLOB,
  UFS INTEGER,
  PREMIER INTEGER,
  REPLACEMENTS NCLOB,
  PRIMARY KEY (MOBILE_MENU_ID),
  FOREIGN KEY (PARENT_ID) REFERENCES MOBILE_MENUS (MOBILE_MENU_ID),
  FOREIGN KEY (LANG_ID) REFERENCES LANGS (LANG_ID)
)