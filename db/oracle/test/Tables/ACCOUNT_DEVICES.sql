DROP TABLE ACCOUNT_DEVICES;

CREATE TABLE ACCOUNT_DEVICES
(
  ACCOUNT_ID NVARCHAR2(32) NOT NULL,
  DEVICE_ID NVARCHAR2(32) NOT NULL,
  CREATED TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (ACCOUNT_ID,DEVICE_ID),
  FOREIGN KEY (ACCOUNT_ID) REFERENCES ACCOUNTS (ACCOUNT_ID),
  FOREIGN KEY (DEVICE_ID) REFERENCES DEVICES (DEVICE_ID)
);