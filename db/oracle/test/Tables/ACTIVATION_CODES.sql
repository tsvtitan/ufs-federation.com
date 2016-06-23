DROP TABLE ACTIVATION_CODES;
CREATE TABLE ACTIVATION_CODES
(
  ACTIVATION_CODE_ID NVARCHAR2(32) NOT NULL ENABLE, 
	SESSION_ID NVARCHAR2(32) NOT NULL ENABLE, 
	ACTIVATION_CODE VARCHAR2(32 BYTE), 
	DATE_BEGIN TIMESTAMP, 
	DATE_END TIMESTAMP,
  ACTIVATED SMALLINT DEFAULT(0) NOT NULL,
  DATA NVARCHAR2(1000),
  CONSTRAINT ACTIVATION_CODES_PK PRIMARY KEY (ACTIVATION_CODE_ID)
);

