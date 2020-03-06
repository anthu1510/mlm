<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $res = DB::table('node')->where('aadhar',$validateValue)->get();
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
        $res = DB::table('node')->where('pan',$validateValue)->get();
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
        $res = DB::table('node')->where('distributor_id',$validateValue)->get();
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
        $res = DB::table('coupon')->where(['coupon_code'=>$validateValue,'status'=>'active'])->get();
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
        $id=\request()->value;
        $res=DB::table('node')->select('name','mobile','address')->where('distributor_id',$id)->first();

        if(!empty($res)){
            return json_encode($res);
        } else {
            echo 'error';
        }

    }


}
