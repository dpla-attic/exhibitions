-- rename "DPLA Exhibit Page" layout to "DPLA Theme page"
update omeka_exhibit_pages set layout = "dpla-theme-page" where layout = "dpla-exhibition-page";

-- copy data form "Short description" to "Long description"
-- step #1
create table tmp_with_data (select * from `omeka_exhibit_page_entries`
where `order` = 1 and `text` is not null and `text` <> "" and `page_id`  in
  (
  SELECT `page_id` from `omeka_exhibit_page_entries` where `order` = 2 and (`text` is null or `text` = "")
  )
);

-- step #2
alter table `omeka_exhibit_page_entries` add column need_update bool;

-- step #3
create table tmp_without_data ( select * from `omeka_exhibit_page_entries`
WHERE  `order` = 2 and (`text` is null or `text` = "") and `page_id` in
  (
  SELECT `page_id` from `omeka_exhibit_page_entries` where `order` = 1 and `text` is not null and `text` <> ""
  )
);

-- step #4
update `omeka_exhibit_page_entries` set need_update = true where id in (select id from `tmp_without_data`);

-- step #5
update `omeka_exhibit_page_entries` inner join tmp_with_data on tmp_with_data.page_id = omeka_exhibit_page_entries.page_id set omeka_exhibit_page_entries.text = tmp_with_data.text where omeka_exhibit_page_entries.need_update = true

-- step #6: cleanup
drop table tmp_with_data;
drop table tmp_without_data;