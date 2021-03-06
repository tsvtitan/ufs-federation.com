DROP TYPE TO_CLIENT_SHARES;

CREATE OR REPLACE TYPE TO_CLIENT_SHARES AS OBJECT 
( 
  SHARE_NAME NVARCHAR2(100),
  SHARE_TYPE NVARCHAR2(100),
  SHARE_INFO NVARCHAR2(250),
  INCOMING NUMERIC(15,7),
  RECEIPTS NUMERIC(15,7),
  CHARGES NUMERIC(15,7),
  OUTGOING NUMERIC(15,7),
  PRICE NUMERIC(15,7),
  COST NUMERIC(15,7), 
  NKD NUMERIC(15,2),
  NKD_SUM NUMERIC(15,2),
  NKD_COST NUMERIC(15,7)
);  