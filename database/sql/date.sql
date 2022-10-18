update posts  SET visible_after = CAST(NOW() AS DATETIME) where 1;


update posts  SET visible_until = CAST(NOW() + INTERVAL 50 DAY AS DATETIME) where 1;


update posts  SET created_at = CAST(NOW() + INTERVAL 20 DAY AS DATETIME) where 1;

UPDATE files
SET
    `url` = REPLACE(url,
                    '/home/roman/www/privateNetwork/public/',
                    '')
WHERE
    1;

update categories set `name` = concat('public/', `name`) where 1;
