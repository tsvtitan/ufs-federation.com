DROP VIEW V_MOBILE_TABLES;

CREATE OR REPLACE VIEW V_MOBILE_TABLES
AS
SELECT MT.*,
       MM.DESCRIPTION AS MENU_DESCRIPTION,
       MM.LANG_ID
  FROM MOBILE_TABLES MT
  JOIN MOBILE_MENUS MM ON MM.MOBILE_MENU_ID=MT.MOBILE_MENU_ID
  