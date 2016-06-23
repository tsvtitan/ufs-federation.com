create or replace PROCEDURE CHANGE_PASSWORD 
(
  --SESSION_ID IN NVARCHAR2 
  ACCOUNT_ID NVARCHAR2
, NEW_PASSWORD IN NVARCHAR2 
) AS
  --ACCOUNT_ID NVARCHAR2(32);
  NEW_PASSWORD_CODE VARCHAR2(32);
BEGIN
  
  /*FOR INC IN (SELECT ACCOUNT_ID
              FROM SESSIONS
              WHERE SESSION_ID = CHANGE_PASSWORD.SESSION_ID) LOOP
    ACCOUNT_ID := INC.ACCOUNT_ID;
    EXIT;
  END LOOP;*/
  
  NEW_PASSWORD_CODE := NEW_PASSWORD;
  NEW_PASSWORD_CODE := SYS.DBMS_OBFUSCATION_TOOLKIT.MD5(INPUT=>UTL_RAW.CAST_TO_RAW(NEW_PASSWORD_CODE));
  
  UPDATE ACCOUNTS SET PASS = NEW_PASSWORD_CODE
  WHERE ACCOUNTS.ACCOUNT_ID = CHANGE_PASSWORD.ACCOUNT_ID;
  
  COMMIT;
  
END CHANGE_PASSWORD;