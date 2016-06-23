DROP VIEW V_MENUS;

CREATE OR REPLACE VIEW V_MENUS
AS
 SELECT M1.*, 
        (CASE WHEN M1.NEW_UP_TO IS NULL THEN 1 ELSE (CASE WHEN M1.NEW_UP_TO>=CURRENT_TIMESTAMP THEN 1 ELSE 0 END) END) AS IS_NEW,
        M2.NAME AS PARENT_NAME,
        LEVEL AS "LEVEL", 
        DECODE(M1.NAME,NULL,'/',SUBSTR(SYS_CONNECT_BY_PATH(M1.NAME, '/'),2)) AS PATH,
        L.NAME AS LANG_NAME,
        VP.PATH AS LINK/*,
        (CASE WHEN M1.INVISIBLE IS NULL THEN VM.PARENT_INVISIBLE ELSE M1.INVISIBLE END) AS PARENT_INVISIBLE*/
   FROM MENUS M1
   LEFT JOIN MENUS M2 ON M2.MENU_ID=M1.PARENT_ID
   LEFT JOIN LANGS L ON L.LANG_ID=M1.LANG_ID
   LEFT JOIN V_PATHS VP ON VP.PATH_ID=M1.PATH_ID
  START WITH M1.PARENT_ID IS NULL
CONNECT BY M1.PARENT_ID = PRIOR M1.MENU_ID     