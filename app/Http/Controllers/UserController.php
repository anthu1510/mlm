<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;

class UserController extends Controller
{
    public function index()
    {
        return view('Login.login');
    }

    public function login()
    {
        $req = \request()->all();

        $data = array(
            'email' =>  $req['email'],
            'password' => md5($req['password'])
        );

        $res = DB::table('users')->where($data)->first();

        if($res != null){
            \request()->session()->put('loggedin',$res->user_id);
              return redirect('dashboard');
        } else {
            $message = 'error|Login Failed..!! Enter Correct Email and Password...!!!';
        }
        return $this->FlshMessage($message);
    }

    public function forgotPassword()
    {
        return view('Login.forgot_password');
    }

    public function forgotcheck(Request $request)
    {
        $check = DB::table('users')->where('email', $request->input('email'))->first();

        if(!empty($check)){
            $code = rand(111111,999999);
            $res = DB::table('users')->where('email',$request->input('email'))->update(['reset_code' => $code]);
            if($res == 1){
               //$send = $this->forgotEmail($check->email,$code);
                    //if($send == 1){
                        $request->session()->put('reset_id', $check->user_id);
                        return redirect('/forgotverify');
                    //} else {
                        //$message = 'error| Error Sending Reset Code... Try again';
                   //}
            } else {
                $message = 'error| Error Please Try Again...';
            }

        } else {
            $message = 'error|This Email Address Not Exists...';
        }
        return $this->FlshMessage($message);
    }

    public function forgotVerify()
    {
        return view('Login.forgot_password_check');
    }

    public function forgotVerifycheck()
    {
        return view('Login.forgot_password_verify');
    }

    public function resetCodeCheck(Request $request)
    {
        $code = $request->input('reset_code');
        $reset_id = $request->session()->get('reset_id');
        $data = array(
            'user_id' => $reset_id,
            'reset_code' => $code
        );
        $res = DB::table('users')->where($data)->first();

        if(!empty($res)){
           return redirect('/forgotverifycheck');
        } else {
            $message = 'error|Error Please Enter Valid Reset Code...';
            return $this->FlshMessage($message);
        }
    }

    public function ResetPasswordUpdate(Request $request)
    {
        $reset_id = $request->session()->get('reset_id');

            if(isset($reset_id)){
                $newpassword = $request->input('cpass');
                $res = DB::table('users')->where('user_id', $reset_id)->update(['password' => md5($newpassword)]);
                    if($res == 1){
                        DB::table('users')->where('user_id', $reset_id)->update(['reset_code' => '']);
                        $request->session()->forget('reset_id');
                        $message = 'message| Password Reset Successfully...';
                        return redirect('/')->with('message', $message);
                    }
            } else {
                $message = 'error| Password Reset Failed Try Again...';
                return redirect('/')->with('message', $message);
            }
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

    private function FlshMessage($message)
    {
        return Redirect::back()->with('message', $message);
    }

    public function forgotEmail($email,$code)
    {
        $reset = 'your Forgot Password Reset Code is: '.'<strong>'.$code.'</strong>';
        $to = $email;
        $subject = 'Forgot Password Reset';
        $body = $reset;

//$headers = 'From: bkarthik85@gmail.com' . "\r\n" ;
        $headers = 'From: '.'Karthik'.'<bkarthik85@gmail.com>' . "\r\n" ;
        $headers .='Reply-To: '. $to . "\r\n" ;
        $headers .='X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

        if(mail($to, $subject, $body,$headers)){
           return 1;
        } else {
            return 0;
        }
    }

    // Users
    public  function Users()
    {
        return view('dashboard.users.index');
    }

    public function UsersServerSide()
    {
        $res = DB::select(DB::raw("SELECT * FROM `users`"));
        return datatables()->of($res)->toJson();
    }

    public function Store()
    {
        $req = \request()->all();

        $data = array(
            'name' => $req['name'],
            'email' => $req['email'],
            'password' => md5($req['pass']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        );

        $res = DB::table('users')->insert($data);

        if($res == 1){
            $message = 'message|User Inserted Successfull...';
        } else {
            $message = 'error|User Inserted Error...';
        }

        return $this->FlshMessage($message);
    }

    public function UserEdit()
    {
        $id = \request()->id;
        $res = DB::table('users')->where('user_id', $id)->first();
        echo json_encode($res);
    }

    public function Update()
    {
        $req = \request()->all();
        $id = $req['uid'];

        $data = array(
            'name' => $req['name_edit'],
            'email' => $req['email_edit'],
            'updated_at' => Carbon::now()
        );

        if(isset($req['pass_edit'])){
            $data['password'] = md5($req['pass_edit']);
        }

        $res = DB::table('users')->where('user_id', $id)->update($data);

        if($res == 1){
            $message = 'message|User Updated Successfull...';
        } else {
            $message = 'error|User Updated Error...';
        }

        return $this->FlshMessage($message);
    }

    public function UserDelete()
    {
        $id = \request()->id;
        $res = DB::table('users')->delete('user_id',$id);
       echo $res;
    }

    public function logout()
    {
        request()->session()->forget('loggedin');
        return redirect('/');
    }
}
