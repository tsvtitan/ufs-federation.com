DROP TABLE PAGE_ANALOGS;

CREATE TABLE PAGE_ANALOGS
(
  PAGE_ID NVARCHAR2(32) NOT NULL,
  LANG_ID CHAR(2) NOT NULL,
  ANALOG_ID NVARCHAR2(32) NOT NULL,
  PRIMARY KEY (PAGE_ID,LANG_ID),
  FOREIGN KEY (PAGE_ID) REFERENCES PAGES (PAGE_ID),
  FOREIGN KEY (LANG_ID) REFERENCES LANGS (LANG_ID),
  FOREIGN KEY (ANALOG_ID) REFERENCES PAGES (PAGE_ID)
) 

