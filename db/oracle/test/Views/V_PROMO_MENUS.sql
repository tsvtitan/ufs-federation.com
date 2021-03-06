DROP VIEW V_PROMO_MENUS;

CREATE OR REPLACE VIEW V_PROMO_MENUS
AS
SELECT PM.*,
        P.TITLE,
        VP1.PATH,
        VP2.PATH AS LINK
  FROM PROMO_MENUS PM
  JOIN PAGES P ON P.PAGE_ID=PM.PAGE_ID
  JOIN V_PATHS VP1 ON VP1.PATH_ID=P.PAGE_ID
  JOIN V_PATHS VP2 ON VP2.PATH_ID=PM.PATH_ID