DROP PROCEDURE PROCESS_MESSAGE;

CREATE OR REPLACE PROCEDURE PROCESS_MESSAGE
(
  MESSAGE_ID IN NVARCHAR2,
  HANDLER_CLASS OUT NVARCHAR2
)
AS 
  EMAIL_PATTERN NVARCHAR2(100):='[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z]{2,4}';
  BOUNCED NVARCHAR2(7):='bounced';
  SPAMMED NVARCHAR2(7):='spammed';
  NEED_LOCKED BOOLEAN:=FALSE;
BEGIN
   
  IF (MESSAGE_ID IS NOT NULL) THEN 
  
    FOR INC IN (SELECT M.SENDER_CONTACT, M.RECIPIENT_CONTACT, 
                       M.SENT, M.DELIVERED, M.ERROR, M.CHANNEL_ID,
                       CH.DIRECTION
                  
                  FROM MESSAGES M
                  JOIN CHANNELS CH ON CH.CHANNEL_ID=M.CHANNEL_ID
                  
                 WHERE M.MESSAGE_ID = PROCESS_MESSAGE.MESSAGE_ID) LOOP

      IF (UPPER(INC.DIRECTION)='OUT') THEN 
      
        IF (INC.SENT IS NOT NULL) THEN
        
          /*IF ((LOWER(SUBSTR(INC.ERROR,1,LENGTH(BOUNCED)))=BOUNCED) OR 
              (LOWER(SUBSTR(INC.ERROR,1,LENGTH(SPAMMED)))=SPAMMED)) THEN
            
            UNSUBSCRIBE(INC.RECIPIENT_CONTACT);
            
            NEED_LOCKED:=TRUE;
            
          END IF;*/

          IF (LOWER(SUBSTR(INC.ERROR,1,LENGTH(SPAMMED)))=SPAMMED) THEN
            
            UNSUBSCRIBE(INC.RECIPIENT_CONTACT);
            
            NEED_LOCKED:=TRUE;
            
          END IF;
          
        END IF;
        
      ELSE
        
        IF (LOWER(INC.SENDER_CONTACT)='tsv@ufs-financial.ch') THEN
          HANDLER_CLASS:='SFtpMessageHandler';
        ELSE
          HANDLER_CLASS:=NULL;
        END IF;
        
      END IF;
      
      EXIT;
      
    END LOOP;  
    
    IF (NEED_LOCKED) THEN
    
      UPDATE MESSAGES
         SET LOCKED=CURRENT_TIMESTAMP
       WHERE MESSAGE_ID = PROCESS_MESSAGE.MESSAGE_ID;
                  
      COMMIT;
      
    END IF;  
    
  END IF;
  
END;
