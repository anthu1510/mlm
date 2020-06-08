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
                                GROUP BY t.node_id HAVING SUM(t.amount) >=1500) temp
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
                            WHERE t.cdate BETWEEN '2020-03-03' AND DATE_ADD('2020-06-30', INTERVAL 1 DAY)
                              AND t.status = 'credit'
                            GROUP BY t.node_id
                            HAVING SUM(t.amount) >= 1500) temp
                           on tt.node_id = temp.nodeid
             order by tt.node_id);


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
                            WHERE t.cdate < '2020-05-30'
                              AND t.status = 'credit'
                            GROUP BY t.node_id
                            HAVING SUM(t.amount) >= 1500) temp
                           on tt.node_id = temp.nodeid
             order by tt.node_id);


select node_id,node_name, sum(amount) from transaction where status='payout' group by node_id ;

/*update transaction set status='credit';*/


select t.id,
       t.node_id,
       t.node_code,
       t.node_name,
       t.coupon,
       n.name,
       n.distributor_id,
       t.amount,DATE_FORMAT(t.cdate,'%d-%m-%Y' ) as 'cdate',
       t.payout_date,t.status
from transaction t join node n on(t.coupon=n.coupon_code) ORDER BY t.id desc;



SELECT t.node_id,
       n.name,
       n.distributor_id,
       n.f_name,
       n.aadhar,
       n.mobile,
       SUM(t.amount) AS total
FROM node n
         JOIN transaction t ON (t.node_id = n.id)
WHERE t.cdate BETWEEN '2020-03-03' AND DATE_ADD('2020-05-30', INTERVAL 1 DAY)
  AND t.status = 'credit'
GROUP BY t.node_id
HAVING SUM(t.amount) >= 500;


select n.id, n.name, n.distributor_id, n.f_name, n.aadhar, n.mobile, SUM(t.amount) as total FROM node n JOIN transaction t ON (t.node_id=n.id) WHERE t.cdate BETWEEN '2020-03-03' AND DATE_ADD('2020-05-30', INTERVAL 1 DAY) AND t.status='credit' GROUP BY t.node_id HAVING SUM(t.amount) >=500

SELECT t.node_id,
       n.name,
       n.distributor_id,
       n.f_name,
       n.aadhar,
       n.mobile,
       SUM(t.amount) AS total
FROM node n
         JOIN transaction t ON (t.node_id = n.id)
WHERE t.cdate BETWEEN '2020-03-03' AND DATE_ADD('2020-05-30', INTERVAL 1 DAY)
  AND t.status = 'credit'
GROUP BY t.node_id

HAVING SUM(t.amount) >= 500

select * FROM transaction;
select sum(amount),node_id,node_name from transaction GROUP BY   node_id

select * FROM transaction WHERE node_id=1

SELECT t.coupon,
       t.amount,
       DATE_FORMAT(t.cdate, '%d-%m-%Y') AS 'cdate',
       payout_date,
       n.name,
       t.status,
       n.distributor_id,
       n.id
FROM transaction t
         JOIN node n ON (t.node_id = n.id)
WHERE t.node_id = 1

SELECT t.node_id,
       t.node_code,
       t.node_name,
       t.coupon,
       n.name,
       n.distributor_id,
       t.amount,
       DATE_FORMAT(t.cdate, '%d-%m-%Y') AS 'cdate'
FROM transaction t
         JOIN node n ON (t.node_id = n.id)





select * from transaction tt join (select
                                       SUM(t.amount) as total,
                                       n.id as nodeid,
                                       n.name,
                                       n.distributor_id,
                                       n.f_name,
                                       n.aadhar,
                                       n.mobile
                                   FROM node n  JOIN  transaction t ON (t.node_id=n.id)
                                   WHERE t.cdate < '2020-05-30'
                                     AND t.status='credit'
                                   GROUP BY t.node_id HAVING SUM(t.amount) >=500) temp
                                  on tt.node_id=temp.nodeid
order by tt.node_id;


select tt.id
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
               WHERE t.cdate < '2020-03-14'
                 AND t.status = 'credit'
               GROUP BY t.node_id
               HAVING SUM(t.amount) >= 500) temp
              on tt.node_id = temp.nodeid


select SUM(t.amount) as total,
       n.id          as nodeid,
       n.name,
       n.distributor_id,
       n.f_name,
       n.aadhar,
       n.mobile
FROM node n
         JOIN transaction t ON (t.node_id = n.id)
WHERE t.cdate < '2020-03-14'
  AND t.status = 'credit'
GROUP BY t.node_id
HAVING SUM(t.amount) >= 500;




select sum(amount) from transaction WHERE  status='payout';
select * from transaction


select * from transaction ttt where ttt.id in(
select tt.id
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
               WHERE t.cdate < '2020-05-30'
                 AND t.status = 'credit'
               GROUP BY t.node_id
               HAVING SUM(t.amount) >= 1500) temp
              on tt.node_id = temp.nodeid
order by tt.node_id);

SELECT * from transaction
where node_id in (select tt.id
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
                            WHERE t.cdate < '2020-05-30'
                              AND t.status = 'credit'
                            GROUP BY t.node_id
                            HAVING SUM(t.amount) >= 1500) temp
                           on tt.node_id = temp.nodeid
             order by tt.node_id);


select * from transaction WHERE node_id in(
SELECT t.node_id

FROM node n
         JOIN transaction t ON (t.node_id = n.id)
WHERE t.cdate BETWEEN '$from_date' AND DATE_ADD('2020-05-13', INTERVAL 1 DAY)
  AND t.status = 'credit'
GROUP BY t.node_id,
         n.name,
         n.distributor_id,
         n.f_name,
         n.aadhar,
         n.mobile
HAVING SUM(t.amount) >= 500 ) ORDER BY  node_id;


SELECT * from transaction WHERE node_id in(
SELECT t.node_id
FROM node n
         JOIN transaction t ON (t.node_id = n.id)
WHERE t.cdate <= DATE_ADD('2020-05-13', INTERVAL 1 DAY)
  AND t.status = 'credit'
GROUP BY t.node_id,
         n.name,
         n.distributor_id,
         n.f_name,
         n.aadhar,
         n.mobile
HAVING SUM(t.amount) >= 500) AND cdate <= DATE_ADD('2020-05-13', INTERVAL 1 DAY) ;



update transaction
set status='payout',payout_date=CURDATE()
where id in(
    SELECT t.node_id
    FROM node n
             JOIN transaction t ON (t.node_id = n.id)
    WHERE t.cdate <= DATE_ADD('2020-05-13', INTERVAL 1 DAY)
      AND t.status = 'credit'
    GROUP BY t.node_id,
             n.name,
             n.distributor_id,
             n.f_name,
             n.aadhar,
             n.mobile
    HAVING SUM(t.amount) >= 500) AND cdate <= DATE_ADD('2020-05-13', INTERVAL 1 DAY) ;



update transaction
set status='payout',payout_date=CURDATE()
where node_id in(
    SELECT t.node_id
    FROM node n
             JOIN transaction t ON (t.node_id = n.id)
    WHERE t.cdate <= DATE_ADD('2020-03-13', INTERVAL 1 DAY)
      AND t.status = 'credit'
    GROUP BY t.node_id,
             n.name,
             n.distributor_id,
             n.f_name,
             n.aadhar,
             n.mobile
    HAVING SUM(t.amount) >= 500) AND cdate <= DATE_ADD('2020-03-13', INTERVAL 1 DAY);



SELECT node_id, sum(amount)
FROM transaction WHERE status='payout' GROUP BY node_id;

SELECT sum(amount) FROM transaction WHERE status='payout';

update transaction set status='credit' ,payout_date=null;



/*// ##################### waste i dont know why i create #########################3*/
/*select tt.id
from transaction tt
         join (select SUM(t.amount) as total,
                      t.node_id          as nodeid
               FROM node n
                        JOIN transaction t ON (t.node_id = n.id)
               WHERE t.cdate < DATE_ADD('2020-05-13', INTERVAL 1 DAY)
                 AND t.status = 'credit'
               GROUP BY t.node_id
               HAVING SUM(t.amount) >= 500) temp
              on tt.node_id = temp.nodeid
order by tt.node_id
*/


update transaction
set status='payout',payout_date=CURDATE()
where node_id in(
    SELECT t.node_id
    FROM node n
             JOIN transaction t ON (t.node_id = n.id)
    WHERE t.cdate <= DATE_ADD('2020-03-13', INTERVAL 1 DAY)
      AND t.status = 'credit'
    GROUP BY t.node_id,
             n.name,
             n.distributor_id,
             n.f_name,
             n.aadhar,
             n.mobile
    HAVING SUM(t.amount) >= 500) AND cdate <= DATE_ADD('2020-03-13', INTERVAL 1 DAY)
