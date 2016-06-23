DROP VIEW V_PROMO_BANNERS;

CREATE OR REPLACE VIEW V_PROMO_BANNERS
AS
SELECT PB.*,
        P.TITLE,
        VP1.PATH,
        VP2.PATH AS IMAGE_PATH,
        VP3.PATH AS LINK
  FROM PROMO_BANNERS PB 
  JOIN PAGES P ON P.PAGE_ID=PB.PAGE_ID
  JOIN V_PATHS VP1 ON VP1.PATH_ID=P.PAGE_ID
  JOIN V_PATHS VP2 ON VP2.PATH_ID=PB.IMAGE_ID
  LEFT JOIN V_PATHS VP3 ON VP3.PATH_ID=PB.PATH_ID 