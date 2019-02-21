select * from `data` where `file_url` in (
    select `file_url` from `data`
    group by `file_url` having count(*) > 1
) and `file_url` not like '%kosong%' and `file_url` not like '%nophoto%' order by `file_url` asc, `dapil` asc, `no_urut` asc, `id` asc