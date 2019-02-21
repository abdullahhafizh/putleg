select * from `data` where `nama_caleg` in (
    select `nama_caleg` from `data`
    group by `nama_caleg` having count(*) > 1
) order by `dapil` asc, `no_urut` asc, `id` asc