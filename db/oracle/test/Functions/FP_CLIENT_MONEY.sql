DROP FUNCTION FP_CLIENT_MONEY;

CREATE OR REPLACE FUNCTION FP_CLIENT_MONEY
(
  AGREEMENT_ID NVARCHAR2,
  FROM_DATE TIMESTAMP,
  TILL_DATE TIMESTAMP 
)
RETURN TT_CLIENT_MONEY
PIPELINED
AS 
  Rec TO_CLIENT_MONEY:=TO_CLIENT_MONEY(NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,
                                       NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
  Cur INTEGER; 
  F NVARCHAR2(50);
  T NVARCHAR2(50);
  ID_IN_SYSTEM NVARCHAR2(100);
  DB_LINK VARCHAR2(100);
  S1 NVARCHAR2(1000);
BEGIN
  
  FOR Inc IN (SELECT A.ID_IN_SYSTEM, S.DB_LINK
                 FROM AGREEMENTS A
                 JOIN SYSTEMS S ON S.SYSTEM_ID=A.SYSTEM_ID
                WHERE A.AGREEMENT_ID=FP_CLIENT_MONEY.AGREEMENT_ID) LOOP
    ID_IN_SYSTEM:=Inc.ID_IN_SYSTEM;
    DB_LINK:=Inc.DB_LINK;
    EXIT;    
  END LOOP;

  IF ((ID_IN_SYSTEM IS NOT NULL) AND (DB_LINK IS NOT NULL)) THEN
  
    Cur:=DBMS_HS_PASSTHROUGH.OPEN_CURSOR@SPECTRE_UFS; 
   
    F:=TO_CHAR(FROM_DATE,q'|'DD.MM.YYYY'|'); 
    T:=TO_CHAR(TILL_DATE,q'|'DD.MM.YYYY'|');
    
    /*ID_IN_SYSTEM:=13252;  -- ID ????????????????
    F:=TO_CHAR(to_date('01.08.2013'),q'|'DD.MM.YYYY'|');
    T:=TO_CHAR(to_date('31.08.2013'),q'|'DD.MM.YYYY'|');*/
    
    S1:='SELECT VAL_NAME CURRENCY_NAME,
                BF_BALANCE,
                INPUT_CASH,
                TRANSFER_PLUS,
                SELL_SHARES,
                INCOME,
                OTHER_RECEIPTS,
                OUTPUT_CASH,
                TRANSFER_PLUS,
                BUY_SHARES,
                BROKER_REWARD,
                COMMISION_KO,
                COMMISION_TC,
                COMMISION_DR,
                OTHER_CHARGES,
                NDFL,
                CF_BALANCE           
           FROM UFS_ACCOUNT_MONEY_STATUS('||ID_IN_SYSTEM||','||F||','||T||')';

    DBMS_HS_PASSTHROUGH.PARSE@SPECTRE_UFS(Cur,S1);
    
    WHILE (DBMS_HS_PASSTHROUGH.FETCH_ROW@SPECTRE_UFS(Cur)>0) LOOP
  
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,1,Rec.CURRENCY_NAME);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,2,Rec.BEFORE_BALANCE);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,3,Rec.IN_CASH);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,4,Rec.TRANSFER_PLUS);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,5,Rec.SELL_SHARES);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,6,Rec.INCOME);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,7,Rec.OTHER_RECEIPTS);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,8,Rec.OUT_CASH);      
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,9,Rec.TRANSFER_MINUS);      
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,10,Rec.BUY_SHARES);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,11,Rec.BROKER_FEES);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,12,Rec.KO_FEES);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,13,Rec.TC_FEES);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,14,Rec.DR_FEES);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,15,Rec.OTHER_CHARGES);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,16,Rec.NDFL);
      DBMS_HS_PASSTHROUGH.GET_VALUE@SPECTRE_UFS(Cur,17,Rec.AFTER_BALANCE);
      
      PIPE ROW (Rec);
      
    END LOOP;
    
    DBMS_HS_PASSTHROUGH.CLOSE_CURSOR@SPECTRE_UFS(Cur); 
    
  END IF;  
  
EXCEPTION
  WHEN OTHERS THEN DBMS_HS_PASSTHROUGH.CLOSE_CURSOR@SPECTRE_UFS(Cur); 
  RAISE;
END;