DROP PROCEDURE I_ACCOUNT;

CREATE OR REPLACE PROCEDURE I_ACCOUNT 
(
  ACCOUNT_ID IN ACCOUNTS.ACCOUNT_ID%TYPE,
  CREATED IN ACCOUNTS.CREATED%TYPE,
  IS_ROLE IN ACCOUNTS.IS_ROLE%TYPE,
  SURNAME IN ACCOUNTS.SURNAME%TYPE,
  NAME IN ACCOUNTS.NAME%TYPE,
  PATRONYMIC IN ACCOUNTS.PATRONYMIC%TYPE,
  LOGIN IN ACCOUNTS.LOGIN%TYPE,
  PASS IN ACCOUNTS.PASS%TYPE,
  PHONE IN ACCOUNTS.PHONE%TYPE,
  EMAIL IN ACCOUNTS.EMAIL%TYPE,
  DESCRIPTION IN ACCOUNTS.DESCRIPTION%TYPE,
  LOCKED IN ACCOUNTS.LOCKED%TYPE,
  LOCK_REASON IN ACCOUNTS.LOCK_REASON%TYPE
) 
AS 
BEGIN
/*  INSERT INTO ACCOUNTS (ACCOUNT_ID)
                VALUES (ACCOUNT_ID); */
  COMMIT;                
END;