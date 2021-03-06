DROP VIEW V_SUBSCRIPTIONS;

CREATE OR REPLACE VIEW V_SUBSCRIPTIONS 
AS
SELECT S.*,
       A.LOGIN,
       A.EMAIL,
       A.PHONE,
       A.NAME
  FROM SUBSCRIPTIONS S
  JOIN ACCOUNTS A ON A.ACCOUNT_ID=S.ACCOUNT_ID
