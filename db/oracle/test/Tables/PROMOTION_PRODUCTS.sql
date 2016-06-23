DROP TABLE PROMOTION_PRODUCTS;

CREATE TABLE PROMOTION_PRODUCTS
(
  PROMOTION_PRODUCT_ID NVARCHAR2(32) NOT NULL,
  PROMOTION_TYPE_ID  NVARCHAR2(32) NOT NULL,
  PROMOTION_COMPANY_ID  NVARCHAR2(32) NOT NULL,
  CREATED TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  BEGIN TIMESTAMP,
  END TIMESTAMP,
  LOCKED TIMESTAMP,
  TIMEOUT INTEGER,
  PRIORITY INTEGER, 
  PRIMARY KEY (PROMOTION_PRODUCT_ID),
  FOREIGN KEY (PROMOTION_TYPE_ID) REFERENCES PROMOTION_TYPES(PROMOTION_TYPE_ID),
  FOREIGN KEY (PROMOTION_COMPANY_ID) REFERENCES PROMOTION_COMPANIES(PROMOTION_COMPANY_ID)
) 