DROP VIEW V_PAGE_TABLES;

CREATE OR REPLACE VIEW V_PAGE_TABLES
AS
SELECT PT.*
  FROM PAGE_TABLES PT;