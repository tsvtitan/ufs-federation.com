DROP VIEW V_PAGE_ANALOGS;

CREATE OR REPLACE VIEW V_PAGE_ANALOGS
AS
SELECT PA.*,
       VP.PATH 
  FROM PAGE_ANALOGS PA
  JOIN PAGES P1 ON P1.PAGE_ID=PA.PAGE_ID
  JOIN PAGES P2 ON P2.PAGE_ID=PA.ANALOG_ID
  JOIN V_PATHS VP ON VP.PATH_ID=P2.PAGE_ID