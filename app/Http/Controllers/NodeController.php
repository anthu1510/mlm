<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Node;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use Psy\Util\Json;

class NodeController extends Controller
{

    public function Create()
    {
        return view('dashboard.node.new');
    }


    //
    public function AadharCheack()
    {
        /* RECEIVE VALUE */
        $validateValue = $_REQUEST['fieldValue'];
        $validateId = $_REQUEST['fieldId'];
        $validateError = "This Pan Card is already Used";
        $validateSuccess = "This Pan Card  is available";
        $res = DB::table('node')->where('aadhar', $validateValue)->get();
        $result = count($res);

        /* RETURN VALUE */
        $arrayToJs = array();
        $arrayToJs[0] = $validateId;
        if ($result < 4) {        // validate??
            $arrayToJs[1] = true;            // RETURN TRUE
            echo json_encode($arrayToJs);            // RETURN ARRAY WITH success
        } else {
            for ($x = 0; $x < 1000000; $x++) {
                if ($x == 990000) {
                    $arrayToJs[1] = false;
                    echo json_encode($arrayToJs);        // RETURN ARRAY WITH ERROR
                }
            }

        }

    }

    public function PanCheack()
    {
        /* RECEIVE VALUE */
        $validateValue = $_REQUEST['fieldValue'];
        $validateId = $_REQUEST['fieldId'];
        $validateError = "This PAN Card is already Used";
        $validateSuccess = "This PAN Card is available";
        $res = DB::table('node')->where('pan', $validateValue)->get();
        $result = count($res);

        /* RETURN VALUE */
        $arrayToJs = array();
        $arrayToJs[0] = $validateId;
        if ($result < 4) {        // validate??
            $arrayToJs[1] = true;            // RETURN TRUE
            echo json_encode($arrayToJs);            // RETURN ARRAY WITH success
        } else {
            for ($x = 0; $x < 1000000; $x++) {
                if ($x == 990000) {
                    $arrayToJs[1] = false;
                    echo json_encode($arrayToJs);        // RETURN ARRAY WITH ERROR
                }
            }

        }

    }

    public function SponserCheck()
    {
        /* RECEIVE VALUE */
        $validateValue = $_REQUEST['fieldValue'];
        $validateId = $_REQUEST['fieldId'];
        $validateError = "This is Invalid Sponser Id";
        $validateSuccess = "This is Valid Sponser Id";
        $res = DB::table('node')->where('distributor_id', $validateValue)->get();
        $result = count($res);

        /* RETURN VALUE */
        $arrayToJs = array();
        $arrayToJs[0] = $validateId;
        if ($result == 1) {        // validate??
            $arrayToJs[1] = true;            // RETURN TRUE
            echo json_encode($arrayToJs);            // RETURN ARRAY WITH success
        } else {
            for ($x = 0; $x < 1000000; $x++) {
                if ($x == 990000) {
                    $arrayToJs[1] = false;
                    echo json_encode($arrayToJs);        // RETURN ARRAY WITH ERROR
                }
            }

        }

    }

    public function CouponCheck()
    {
        /* RECEIVE VALUE */
        $validateValue = $_REQUEST['fieldValue'];
        $validateId = $_REQUEST['fieldId'];
        $validateError = "This is Invalid Coupon Code";
        $validateSuccess = "This is Valid Coupon Code";
        $res = DB::table('coupon')->where(['coupon_code' => $validateValue, 'status' => 'active'])->get();
        $result = count($res);

        /* RETURN VALUE */
        $arrayToJs = array();
        $arrayToJs[0] = $validateId;
        if ($result == 1) {        // validate??
            $arrayToJs[1] = true;            // RETURN TRUE
            echo json_encode($arrayToJs);            // RETURN ARRAY WITH success
        } else {
            for ($x = 0; $x < 1000000; $x++) {
                if ($x == 990000) {
                    $arrayToJs[1] = false;
                    echo json_encode($arrayToJs);        // RETURN ARRAY WITH ERROR
                }
            }

        }

    }

    public function GetSponser()
    {
        $id = \request()->value;
        $res = DB::table('node')->select('name', 'mobile', 'address', 'l', 'm', 'r', 'id')->where('distributor_id', $id)->first();

        if (!empty($res)) {
            return json_encode($res);
        } else {
            echo 'error';
        }

    }

    public function list()
    {
        return view('dashboard.node.list');
    }
    public function treelist()
    {
        return view('dashboard.node.treelist');
    }
    public function comissionlist()
    {
         \request()->session()->forget("generate_payout");  // call a common function using session so we unset this date field
        return view('dashboard.node.comission_list');
    }

    public function couponcomissionlist()
    {
        return view('dashboard.node.coupon_comission_list');
    }

    public function comission()
    {
        $id=\request()->id;

        return view('dashboard.node.comission')->with('id',$id);
    }



    public function NodeServerSide()
    {
        //$res = DB::select(DB::raw("SELECT * FROM `node`"));
        $res = DB::select(DB::raw("SELECT `id`, `distributor_id`, `sponser_id`, `sponser_name`, `coupon_code`, `p`, `l`, `m`, `r`, `p_id`, `l_id`, `m_id`, `r_id`, `name`, `f_name`, `dob`, `sex`, `aadhar`, `pan`, `address`, `mobile`, `email`, `account_no`, `ifsc_code`, `bank_name`, `branch_name`, `nominee_name`, `nominee_relationship`,DATE_FORMAT( `cdate`, '%d-%m-%Y') as 'cdate', `status` FROM `node`"));
        return datatables()->of($res)->toJson();
    }
    public function NodeTreeServerSide()
    {
        $res = DB::select(DB::raw("SELECT `id`, `distributor_id`, `sponser_id`, `sponser_name`, `coupon_code`, `p`, `l`, `m`, `r`, `p_id`, `l_id`, `m_id`, `r_id`, `name`, `f_name`, `dob`, `sex`, `aadhar`, `pan`, `address`, `mobile`, `email`, `account_no`, `ifsc_code`, `bank_name`, `branch_name`, `nominee_name`, `nominee_relationship`,DATE_FORMAT( `cdate`, '%d-%m-%Y') as 'cdate', `status` FROM `node`"));
        return datatables()->of($res)->toJson();
    }


    public function ComissionServerSide($node_id)
    {
        $ses=\request()->session()->get('generate_payout');
        if(isset($ses))
        {
            $from_date=$ses['from_date'];
            $to_date=$ses['to_date'];

            $wheresess=" AND t.cdate BETWEEN '$from_date' AND DATE_ADD('$to_date', INTERVAL 1 DAY)";

        }
        else
        {
            $wheresess="";
        }


        $res = DB::select(DB::raw("SELECT t.coupon,
                                           t.amount,
                                           DATE_FORMAT(t.cdate, '%d-%m-%Y') AS 'cdate',
                                           payout_date,
                                           n.name,
                                           t.status,
                                           n.distributor_id,
                                           n.id
                                    FROM transaction t
                                             JOIN node n ON (t.node_id = n.id)
                                    WHERE t.node_id = $node_id".$wheresess));
        return datatables()->of($res)->toJson();
    }

    public function CouponComissionServerSide()
    {
        $res = DB::select(DB::raw("SELECT t.node_id,
                                           t.node_code,
                                           t.node_name,
                                           t.coupon,
                                           n.name,
                                           n.distributor_id,
                                           t.amount,
                                           DATE_FORMAT(t.cdate, '%d-%m-%Y') AS 'cdate'
                                    FROM transaction t
                                             JOIN node n ON (t.node_id = n.id)"));
        return datatables()->of($res)->toJson();
    }

    public function NodeEdit()
    {
        $id = \request()->id;
        $res = DB::table('node')->where('id', $id)->first();
        echo json_encode($res);
    }

    public function UpdateSave(Request $request)
    {
        $id = $request->input('id');
        $data = [

            'name' => $request->input('name'),
            'f_name' => $request->input('fname'),
            'dob' => $request->input('dob'),
            'sex' => $request->input('gender'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'aadhar' => $request->input('aadhar'),
            'pan' => $request->input('pan'),
            'address' => $request->input('address'),
            'account_no' => $request->input('accno'),
            'ifsc_code' => $request->input('ifsccode'),
            'bank_name' => $request->input('bankname'),
            'branch_name' => $request->input('branchname'),
            'nominee_name' => $request->input('nominee'),
            'nominee_relationship' => $request->input('relationship'),


        ];


        $res = DB::table('node')->where('id', $id)->update($data);


        if ($res == 1) {
            $message = 'message|Node Updaterd  Successfull...';

        } else {
            $message = 'error|Node Not Updated';
        }

        return $this->FlshMessage($message);

    }

    /*public function Save()
    {
        $req = \request()->all();


        $node_data = [
            'distributor_id' => Node::getDistributorId(),
            'sponser_id' => $req['sponserid'],
            'sponser_name' => $req['sponsername'],
            'coupon_code' => $req['couponcode'],
            'p' => $req['spon_id'],
            'p_id' => $req['sponserid'],
            'name' => $req['name'],
            'f_name' => $req['fname'],
            'dob' => $req['dob'],
            'sex' => $req['gender'],
            'aadhar' => $req['aadhar'],
            'pan' => $req['pan'],
            'address' => $req['address'],
            'mobile' => $req['mobile'],
            'email' => $req['email'],
            'account_no' => $req['accno'],
            'ifsc_code' => $req['ifsccode'],
            'bank_name' => $req['bankname'],
            'branch_name' => $req['branchname'],
            'nominee_name' => $req['nominee'],
            'nominee_relationship' => $req['relationship'],
            'status' => 'active'

        ];


        //print_r($node_data)
        $res = DB::table('node')->insertGetId($node_data);

        //$res_auto=     DB::table('auto_node')->insertGetId($node_data);

        if ($res) {

            DB::insert("INSERT INTO `auto_node` ( `distributor_id`, `sponser_id`, `sponser_name`, `coupon_code`, `p`, `l`, `m`, `r`, `p_id`, `l_id`, `m_id`, `r_id`, `name`, `f_name`, `dob`, `sex`, `aadhar`, `pan`, `address`, `mobile`, `email`, `account_no`, `ifsc_code`, `bank_name`, `branch_name`, `nominee_name`, `nominee_relationship`, `status`)
                           SELECT `distributor_id`, `sponser_id`, `sponser_name`, `coupon_code`, `p`, `l`, `m`, `r`, `p_id`, `l_id`, `m_id`, `r_id`, `name`, `f_name`, `dob`, `sex`, `aadhar`, `pan`, `address`, `mobile`, `email`, `account_no`, `ifsc_code`, `bank_name`, `branch_name`, `nominee_name`, `nominee_relationship`, `status` FROM node where id = $res");

            $user_alot = Node::AsignSponser($req['sponserid'], $res, $node_data['distributor_id'], $req['tree_position']);
            $coupon_update = Coupon::UseCoupon($node_data['coupon_code']);

            Node::setParentCommision($res, 0, $req['couponcode']);
            echo "User Alloted : $user_alot  & Coupon Update:$coupon_update";


            $message = 'message|Node saved  Successfull...';


        } else {
            $message = 'error|Node not saved Error...';
        }

         return $this->FlshMessage($message);


    }*/
    public function save2()
    {
        $req = \request()->all();


        $node_data = [
            'distributor_id' => Node::getDistributorId(),
            'sponser_id' => $req['sponserid'],
            'sponser_name' => $req['sponsername'],
            'coupon_code' => $req['couponcode'],
            'p' => $req['spon_id'],
            'p_id' => $req['sponserid'],
            'name' => $req['name'],
            'f_name' => $req['fname'],
            'dob' => $req['dob'],
            'sex' => $req['gender'],
            'aadhar' => $req['aadhar'],
            'pan' => $req['pan'],
            'address' => $req['address'],
            'mobile' => $req['mobile'],
            'email' => $req['email'],
            'account_no' => $req['accno'],
            'ifsc_code' => $req['ifsccode'],
            'bank_name' => $req['bankname'],
            'branch_name' => $req['branchname'],
            'nominee_name' => $req['nominee'],
            'nominee_relationship' => $req['relationship'],
            'status' => 'active'

        ];


        //print_r($node_data)
        $res = DB::table('node')->insertGetId($node_data);





        if ($res) {
            $res_auto=     DB::table('auto_node')->insertGetId($node_data);

/*$auto_id=  DB::insert("INSERT INTO `auto_node` ( `distributor_id`, `sponser_id`, `sponser_name`, `coupon_code`, `p`, `l`, `m`, `r`, `p_id`, `l_id`, `m_id`, `r_id`, `name`, `f_name`, `dob`, `sex`, `aadhar`, `pan`, `address`, `mobile`, `email`, `account_no`, `ifsc_code`, `bank_name`, `branch_name`, `nominee_name`, `nominee_relationship`,`cdate`, `status`)
                           SELECT `distributor_id`, `sponser_id`, `sponser_name`, `coupon_code`, `p`, `l`, `m`, `r`, `p_id`, `l_id`, `m_id`, `r_id`, `name`, `f_name`, `dob`, `sex`, `aadhar`, `pan`, `address`, `mobile`, `email`, `account_no`, `ifsc_code`, `bank_name`, `branch_name`, `nominee_name`, `nominee_relationship`,`cdate`, `status` FROM node where id = $res")->id;*/

            Node::AsignAutoNodeParrent($res_auto);

            $user_alot = Node::AsignSponser($req['sponserid'], $res, $node_data['distributor_id'], $req['tree_position']);
            $coupon_update = Coupon::UseCoupon($node_data['coupon_code']);

            Node::setParentCommision($res, 0, $req['couponcode']);
            $newNode= new Node();
            $newNode->AutoNodePayoutEligibulity($res_auto);

            echo "User Alloted : $user_alot  & Coupon Update:$coupon_update";


            $message = 'message|Node saved  Successfull...';


        } else {
            $message = 'error|Node not saved Error...';
        }

        return $this->FlshMessage($message);


    }

    public function NodeDeactivate()
    {
        $req = \request()->all();
        $id = $req['id'];
        $data=['status'=>'inactive'];
        $res = DB::table('node')->where('id', $id)->update($data);
    }
    public function NodeActivate()
    {
        $req = \request()->all();
        $id = $req['id'];
        $data=['status'=>'active'];
        $res = DB::table('node')->where('id', $id)->update($data);
    }


    private function FlshMessage($message)
    {
        return Redirect::back()->with('message', $message);
    }

    public function displayAlert()
    {
        if (Session::has('message')) {
            list($type, $message) = explode('|', Session::get('message'));

            $type = $type == 'error' ? 'danger' : 'success';
            $close = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            //return sprintf('<div class="alert alert-'.$type.'">%s</div>', $type, $message);
            //return '<div class="alert alert-'.$type.'">'.$message.'</div>';
            return new HtmlString('<div class="alert alert-dismissible alert-'.$type.'">'.$close.$message.'</div>');
        }

        return '';
    }

    public function getParent1()
    {
        $id = \request()->id;
       // Node::getParentList($id);
       Node::setParentCommision($id);
    }

    public function setTreeValues()
    {
        // master table setup.. no furher use in project
        $no= new Node();
        $no->FillAutoNodeTreeTable(10000);
        $no->FillSilverNodeTreeTable(10000);
        $no->FillGoldNodeTreeTable(10000);


    }
    public function testInsert()
    {
        $test=new Node();
        $test->AutoNodePayout();
    }
}


