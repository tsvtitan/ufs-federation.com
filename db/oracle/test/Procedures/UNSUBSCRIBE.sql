DROP PROCEDURE UNSUBSCRIBE;

CREATE OR REPLACE PROCEDURE UNSUBSCRIBE
(
  CONTACT IN NVARCHAR2
)
AS 
  EMAIL_PATTERN NVARCHAR2(100):='[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z]{2,4}';
  IS_EMAIL NVARCHAR2(100):=NULL;
  Cur INTEGER:=NULL;
  Ret INTEGER:=NULL;
BEGIN 

  IS_EMAIL:=REGEXP_SUBSTR(CONTACT,EMAIL_PATTERN);
          
  IF (IS_EMAIL IS NOT NULL) THEN
     
    UPDATE SUBSCRIPTIONS
       SET FINISHED=CURRENT_TIMESTAMP
     WHERE ACCOUNT_ID IN (SELECT ACCOUNT_ID FROM ACCOUNTS WHERE LOWER(EMAIL)=LOWER(CONTACT))
       AND UPPER(DELIVERY_TYPE)='EMAIL'; 
    
    COMMIT;   
       
    Cur:=DBMS_HS_PASSTHROUGH.OPEN_CURSOR@WWW_UFS; 
    
    DBMS_HS_PASSTHROUGH.PARSE@WWW_UFS(Cur,'update mailing_subscriptions 
                                              set finished=current_timestamp 
                                            where finished is null
                                              and email=?;');
  
    DBMS_HS_PASSTHROUGH.BIND_VARIABLE@WWW_UFS(Cur,1,CONTACT);
    Ret:=DBMS_HS_PASSTHROUGH.EXECUTE_NON_QUERY@WWW_UFS(Cur);
    DBMS_HS_PASSTHROUGH.CLOSE_CURSOR@WWW_UFS(Cur);
    
    COMMIT;
    
  END IF;

EXCEPTION
  WHEN OTHERS THEN 
    IF (Cur IS NOT NULL) THEN
      DBMS_HS_PASSTHROUGH.CLOSE_CURSOR@WWW_UFS(Cur); 
    END IF;
  ROLLBACK;  
  RAISE;  
END;