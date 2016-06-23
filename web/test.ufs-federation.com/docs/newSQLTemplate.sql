
SELECT 
(id+2000) as id,
url,name,content,img_1,img_2,img_3,img_4,img_5,img_6,
promo_title_1,promo_title_2,promo_title_3,promo_title_4,promo_title_5,promo_title_6,
promo_url_1,promo_url_2,promo_url_3,promo_url_4,promo_url_5,promo_url_6,
promo_text_1,promo_text_2,promo_text_3,promo_text_4,promo_text_5,promo_text_6,
promo_hide_1,promo_hide_2,promo_hide_3,promo_hide_4,promo_hide_5,promo_hide_6,
promo_slider_speed,link_to_page,meta_title,meta_keywords,meta_description,
(cat_id+100) as cat_id, (sort_id+1000) as sort_id, (parent_id+1000) as parent_id,
is_delete,is_hide,is_home,sub_page_type,indexes_box,current_tiemstamp as timestamp,
'cn' as lang,contact_id,slider_link_type,redirect,redirect_code,main
FROM `pages` WHERE lang='fr'
ORDER BY `id`  ASC


SELECT (id+1000) as id,
url,name,short_content,content,meta_title,meta_keywords,meta_description,timestamp,'cn' as lang
FROM `news` 
WHERE lang='fr'


SELECT (
id +10000
) AS id, url, name, img, publisher_name, short_content, content, meta_title, meta_keywords, meta_description, TIMESTAMP,  'cn' AS lang
FROM  `press_about_us` 
WHERE lang =  'fr'
LIMIT 100