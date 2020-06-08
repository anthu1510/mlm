<?php

namespace App\Http\Controllers;

use App\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{

    public function updateSave()
    {
        $req=\request()->all();
        $id=$req['id'];
        $cheque_no=$req['chequeno'];
        $tracking_no=$req['trackingno'];
        $collented_on=$req['collectedon'];
        $note=$req['note'];

        $data=array(
            'cheque_no'=>$cheque_no,
            'tracking_no'=>$tracking_no,
            'collected_on'=>$collented_on,
            'note'=>$note

        );

       $res= DB::table('payout_detail')->where('id',$id)->update($data);
       if($res)
       {
           return redirect('dashboard/payout/payoutlist');
       }


    }

    public function payoutEdit()
    {
         $id = \request()->id;

        $res = DB::table('payout_detail')->where('id', $id)->first();
        echo json_encode($res);
        //return [$res];
    }

    public function Payout()
    {
        \request()->session()->forget("generate_payout");  // call a common function using session so we unset this date field
        return view('dashboard.payout.payout');
    }

    public function DateBetween()
    {
        return view('dashboard.payout.generate');
    }

    public function DateBetweenView()
    {
        $req=\request()->all();

        $generate = ['from_date' => $req['from_date'], 'to_date' => $req['to_date']];
        \request()->session()->put('generate_payout',$generate);
        return view('dashboard.payout.generate')->with(compact('generate'));
    }


    public function PayoutGenerate()
    {
        return view('dashboard.payout.payout_generate');
    }

    public function PayoutGenerateView()
    {
        $req=\request()->all();

        $generate = [

            'to_date' => $req['to_date'],
            'amount'=>$req['amount'],
            'comments'=>$req['comments']
        ];
        \request()->session()->put('generate_payout',$generate);
        return view('dashboard.payout.payout_generate')->with(compact('generate'));
    }

    public function MakePayout()
    {
        $req=\request()->all();
        $amount=$req['amount'];
        $to_date=$req['todate'];
        $comments=$req['comments'];

        $dataPayout=array(
            'desc'=>$comments,
            'pdate'=>$to_date,
            );
        $select="SELECT t.node_id,
                                       n.name,
                                       n.distributor_id,
                                       n.f_name,
                                       n.aadhar,
                                       n.mobile,
                                       SUM(t.amount) AS total
                                FROM node n
                                         JOIN transaction t ON (t.node_id = n.id)
                                WHERE t.cdate <= DATE_ADD('$to_date', INTERVAL 1 DAY)
                                  AND t.status = 'credit'
                                GROUP BY t.node_id,
                                         n.name,
                                         n.distributor_id,
                                         n.f_name,
                                         n.aadhar,
                                         n.mobile
                                HAVING SUM(t.amount) >= $amount";


        $dataEligible=DB::select(DB::raw($select));

        $qry="update transaction
                set status='payout',payout_date=CURDATE()
                where node_id in(
                    SELECT t.node_id
                    FROM node n
                             JOIN transaction t ON (t.node_id = n.id)
                    WHERE t.cdate <= DATE_ADD('$to_date', INTERVAL 1 DAY)
                      AND t.status = 'credit'
                    GROUP BY t.node_id,
                             n.name,
                             n.distributor_id,
                             n.f_name,
                             n.aadhar,
                             n.mobile
                    HAVING SUM(t.amount) >= $amount) AND cdate <= DATE_ADD('$to_date', INTERVAL 1 DAY)";
        if($dataEligible)
        {
            $res=DB::select(DB::raw($qry));
            $masterid=Payout::InsertMaster($dataPayout);
            $res=Payout::InsertDeatil($masterid,$to_date,$dataEligible);
        }

        return $res;


    }
    public function PayoutList()
    {
        return view('dashboard.payout.payout_list');
    }





    public function PayoutServerSide()
    {
        $res = DB::select(DB::raw("select t.id,
                                           t.node_id,
                                           t.node_code,
                                           t.node_name,
                                           t.coupon,
                                           t.amount,DATE_FORMAT(t.cdate,'%d-%m-%Y' ) as 'cdate',
                                           t.status,
                                           t.payout_date
                                    from transaction t join node n on(t.node_id = n.id) ORDER BY t.id desc"));
        return datatables()->of($res)->toJson();
    }
    public function PayoutEligibleServerSide()
    {
        $ses=\request()->session()->get('generate_payout');
        $from_date=$ses['from_date'];
        $to_date=$ses['to_date'];
        $res = DB::select(DB::raw("SELECT t.node_id,
                                       n.name,
                                       n.distributor_id,
                                       n.f_name,
                                       n.aadhar,
                                       n.mobile,
                                       SUM(t.amount) AS total
                                FROM node n
                                         JOIN transaction t ON (t.node_id = n.id)
                                WHERE t.cdate BETWEEN '$from_date' AND DATE_ADD('$to_date', INTERVAL 1 DAY)
                                  AND t.status = 'credit'
                                GROUP BY t.node_id,
                                         n.name,
                                         n.distributor_id,
                                         n.f_name,
                                         n.aadhar,
                                         n.mobile
                                HAVING SUM(t.amount) >= 500"));
        return datatables()->of($res)->toJson();
    }
    public function PayoutGenerateServerSide()
    {
        $ses=\request()->session()->get('generate_payout');

        $to_date=$ses['to_date'];
        $amount=$ses['amount'];
        $res = DB::select(DB::raw("SELECT t.node_id,
                                       n.name,
                                       n.distributor_id,
                                       n.f_name,
                                       n.aadhar,
                                       n.mobile,
                                       SUM(t.amount) AS total
                                FROM node n
                                         JOIN transaction t ON (t.node_id = n.id)
                                WHERE t.cdate <= DATE_ADD('$to_date', INTERVAL 1 DAY)
                                  AND t.status = 'credit'
                                GROUP BY t.node_id,
                                         n.name,
                                         n.distributor_id,
                                         n.f_name,
                                         n.aadhar,
                                         n.mobile
                                HAVING SUM(t.amount) >= $amount"));
        return datatables()->of($res)->toJson();
    }
    public function PayoutListServerSide()
    {
        $res = DB::select(DB::raw("select
        pm.`desc`,
       pd.id,
       pd.node_id,
      pd.distributor_id,
        pd.name,
       pd.f_name,
       pd.mobile,
       pd.aadhar,
       pd.total,
       pd.pdate
from payout_detail pd JOIN payout_master pm ON (pd.payout_id=pm.id)"));
        return datatables()->of($res)->toJson();
    }
}
