DROP VIEW V_CONFIRMS;

CREATE OR REPLACE VIEW V_CONFIRMS
AS
SELECT C.*,
        M.RECIPIENT_CONTACT,
        P.PATH
  FROM CONFIRMS C
  LEFT JOIN MESSAGES M ON M.MESSAGE_ID=C.MESSAGE_ID
  LEFT JOIN V_PATHS P ON P.PATH_ID=C.PATH_ID