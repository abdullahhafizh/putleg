select `dapil`, `no_urut`, Count(*)
from `data`
group by `dapil`, `no_urut`
order by Count(*) desc, `dapil` asc, `no_urut` asc