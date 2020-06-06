select * from transaction where status='payout';
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





select * from transaction tt join (select
                                    SUM(t.amount) as total,
                                    n.id as nodeid,
                                    n.name,
                                    n.distributor_id,
                                    n.f_name,
                                    n.aadhar,
                                    n.mobile
                                FROM node n  JOIN  transaction t ON (t.node_id=n.id)
                                WHERE t.cdate BETWEEN '2020-03-03' AND DATE_ADD('2020-05-30', INTERVAL 1 DAY)
                                  AND t.status='credit'
                                GROUP BY t.node_id HAVING SUM(t.amount) >=500) temp
on tt.node_id=temp.nodeid
order by tt.node_id;





update transaction
set status='payout',payout_date=CURDATE()
where id in (select tt.id
             from transaction tt
                      join (select SUM(t.amount) as total,
                                   n.id          as nodeid,
                                   n.name,
                                   n.distributor_id,
                                   n.f_name,
                                   n.aadhar,
                                   n.mobile
                            FROM node n
                                     JOIN transaction t ON (t.node_id = n.id)
                            WHERE t.cdate BETWEEN '2020-03-03' AND DATE_ADD('2020-05-30', INTERVAL 1 DAY)
                              AND t.status = 'credit'
                            GROUP BY t.node_id
                            HAVING SUM(t.amount) >= 500) temp
                           on tt.node_id = temp.nodeid
             order by tt.node_id);

select node_id,node_name, sum(amount) from transaction where status='payout' group by node_id ;

/*update transaction set status='credit'*/

