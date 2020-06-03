select * from transaction;
select * from node;

select
       sum(t.amount) as total,
       n.id,
       n.name,
       n.distributor_id,
       n.f_name,
       n.aadhar,
       n.mobile
from node n  JOIN  transaction t ON (t.node_id=n.id)
WHERE t.cdate BETWEEN '2020-03-03' AND DATE_ADD('2020-05-30', INTERVAL 1 DAY)
AND t.status='credit'
group by t.node_id;


select
    SUM(t.amount) as total,
    n.id,
    n.name,
    n.distributor_id,
    n.f_name,
    n.aadhar,
    n.mobile
FROM node n  JOIN  transaction t ON (t.node_id=n.id)
WHERE t.cdate BETWEEN '2020-03-03' AND DATE_ADD('2020-05-30', INTERVAL 1 DAY)
  AND t.status='credit'
GROUP BY t.node_id HAVING SUM(t.amount) >=500;



