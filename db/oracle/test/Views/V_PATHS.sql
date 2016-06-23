DROP VIEW V_PATHS;

CREATE OR REPLACE VIEW V_PATHS
AS
 SELECT P1.*, P2.NAME AS PARENT_NAME,
         LEVEL AS "LEVEL", 
         CAST(REPLACE(SYS_CONNECT_BY_PATH(DECODE(P1.NAME,NULL,'',P1.NAME),'/'),'//','/') AS NVARCHAR2(1000)) AS PATH
   FROM PATHS P1
   LEFT JOIN PATHS P2 ON P2.PATH_ID=P1.PARENT_ID
  START WITH P1.PARENT_ID IS NULL
CONNECT BY P1.PARENT_ID = PRIOR P1.PATH_ID 