CREATE OR REPLACE PACKAGE PADEG AS 

  PROCEDURE GET_FIO_PARTS_STR(cFIO VARCHAR2, firstName IN OUT VARCHAR2, lastName IN OUT VARCHAR2, middleName IN OUT VARCHAR2 );

END PADEG;
/


CREATE OR REPLACE PACKAGE BODY PADEG AS 
  
  PROCEDURE GET_FIO_PARTS_STR(cFIO VARCHAR2, firstName IN OUT VARCHAR2, lastName IN OUT VARCHAR2, middleName IN OUT VARCHAR2 )
  AS LANGUAGE JAVA NAME 'ufsic.sp.PadegWrapper.getFioPartsStr(java.lang.String, java.lang.String[], java.lang.String[], java.lang.String[])';


END PADEG;
/