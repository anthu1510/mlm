<?php

namespace App\Http\Controllers;

use App\PurchaseOrder;
use App\RmeQuote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
       return view('dashboard.index');
    }

    public function QuoteView()
    {
        $id = \request()->id;
        $master = DB::table('rmequote')->where('id', $id)->first();
        $details = DB::table('rmequote_details')->where('Quote_Number', $master->Quote_Number)->get();

        $data = array(
            'master' => $master,
            'details' => $details
        );

        echo json_encode($data);
    }

    public function QuotePrint($id)
    {
       $master = DB::table('rmequote')->where('id', $id)->first();
       $details = DB::table('rmequote_details')->where('Quote_Number', $master->Quote_Number)->get();

        $logo = "assets/images/logo/logo.png";

        $data = array(
            'master' => $master,
            'details' => $details,
            'logo' => $logo,
            'total_tax_amount' => $this->GSTCalculation($id),
            'bodyheight' => $this->CountHeightPrintPdfBody($details),
            'count' => count($details)
        );

        $pdf = PDF::loadView('dashboard.quote.quote_print', $data);

        return $pdf->download($master->Quote_Number.'.pdf');
    }

    private function GSTCalculation($id)
    {
        $master = DB::table('rmequote')->where('id', $id)->first();

        if($master->IGST == 0){
            $cgst = $master->Total * $master->CGST / 100;
            $sgst = $master->Total * $master->SGST / 100;
            $tax =  $sgst + $cgst;
        } else {
            $igst = $master->Total * $master->IGST / 100;
            $tax = $igst;
        }

        return $tax;
    }

    private function CountHeightPrintPdfBody($count)
    {
        $details = count($count);
        $height = 330;
        $cont = 6;

        $rel = $details - 1;

        if ($rel < $cont) {
            $r = $cont - $rel;
            $inc = $r * 19.5;
            $res = $height + $inc;
        } elseif ($rel > $cont) {
            $r = $rel - $cont;
            $inc = $r * 10;
            $res = $height - $inc;
        } else {
            $res = $height;
        }

       return $res;
    }

    public function getprofile()
    {
        $log = request()->session()->get('loggedin');

        return DB::table('users')->where('user_id',$log)->first();
    }

    public function quote()
    {
        return view('dashboard.quote.index');
    }

    public function QuoteServerSide()
    {
       $res = RmeQuote::all();
        return Datatables::of($res)->make();
    }

    public function purchaseorder()
    {
        return view('dashboard.purchase.index');
    }

    public function PurchaseOrderServerSide()
    {
        $res = PurchaseOrder::all();
        return Datatables::of($res)->make();
    }

    public function EditProfile()
    {
        $id = request()->session()->get('loggedin');
        $res = DB::table('users')->where('user_id',$id)->first();
        return view('dashboard.profile.profile_edit')->with('getuser', $res);
    }

    public function Update()
    {
        $req = request()->all();

        $data = array(
            'name' => $req['u_name'],
            'email' => $req['u_email'],
            'updated_at' => Carbon::now()
        );

        $res = DB::table('users')->where('user_id', $req['u_id'])->update($data);

        if($res == 1){
            $message = 'message|User Updated Successfull...';

        } else {
            $message = 'error|User Updated Error...';
        }

        return $this->FlshMessage($message);
    }

    public function passwordchange()
    {
        return view('dashboard.profile.profile_password_change');
    }

    public function updatepassword()
    {
        $id = request()->session()->get('loggedin');
        $req = request()->all();

        $rows = DB::table('users')->where(['user_id' => $id, 'password' => md5($req['old_pass'])])->count();

        if ($rows == 1) {
            $res = DB::table('users')->where('user_id', $id)->update([ 'password' => md5($req['con_pass'])]);

            if ($res == 1) {
                return Redirect::back()->with('message', 'message|Password Updated...');

            } else {
                return Redirect::back()->with('message', 'error|Password Cannot Updated...');
            }
        } else {
            return Redirect::back()->with('message', 'error|Old Password Cannot Matched...');
        }
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
}
