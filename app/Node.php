<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Node extends Model
{
    //
    public static function getDistributorId()
    {
        $ret= DB::select(DB::raw("select concat('FG' , DATE_FORMAT(now(), '%Y%m%d'),
              LPAD(
                      if(
                              Max(
                                      convert(
                                              mid(distributor_id, 11, length(distributor_id))
                                          , UNSIGNED
                                          )
                                  ) is null, 1, Max(convert(mid(distributor_id, 11, length(distributor_id)), UNSIGNED)) + 1
                          ),
                      6, 0))
           as Code
from node


"));
        $rets=$ret[0];
        return $rets->Code;
    }


    public static function AsignSponser($sponserid,$node_id,$node_code,$tree_possition)
    {

        if($tree_possition=='right')
        {
            $tree_data=[
                'r'=>$node_id,
                'r_id'=>$node_code

            ];
        }
        if($tree_possition=='left')
        {
            $tree_data=[
                'l'=>$node_id,
                'l_id'=>$node_code

            ];
        }
        if($tree_possition=='middle')
        {
            $tree_data=[
                'm'=>$node_id,
                'm_id'=>$node_code

            ];
        }

        $data = [
            'status' => 'alloted'
        ];
        $res = DB::table('node')->where('distributor_id',$sponserid )->update($tree_data);
        if ($res == 1) {
            return 1;

        } else {
            return 0;
        }
    }

    public static function setParentCommision($id, $init = 0,$cupon_code=0)
    {
        $init++;

        if ($id == '') {
            return 1;
        }
        else {

            $res = DB::table('node')->select('id', 'distributor_id', 'sponser_name', 'p', 'p_id', 'name', 'coupon_code')->where('id', $id)->first();
           // echo "<br>" . $res->p_id . " : $res->p ,$init";

            if($res)
            {
                if($res->p >0)
                {
                    $amount = 0;
                    if ($init == 1) {
                        $amount = 300;
                    } elseif ($init == 2) {
                        $amount = 200;
                    } elseif ($init == 3) {
                        $amount = 100;
                    } elseif ($init == 4) {
                        $amount = 50;
                    } elseif ($init == 5) {
                        $amount = 20;
                    }


                    if ($amount >= 20) {
                        $data = [
                            'node_id' => $res->p,
                            'node_code' => $res->p_id,
                            'node_name' => $res->sponser_name,
                            'coupon' => $cupon_code,
                            'amount' => $amount,
                            'status' => 'credit'
                        ];

                        DB::table('transaction')->insert($data);


                    }
                    //print_r("test value <pre>".$res);
                    self::setParentCommision($res->p, $init, $cupon_code);
                }
            }



        }

    }
    /*public static function setAutoParentCommision($id, $init = 0,$cupon_code=0)
    {
        $init++;

        if ($id == '') {
            return 1;
        }
        else {

            $res = DB::table('auto_node')->select('id', 'distributor_id', 'sponser_name', 'p', 'p_id', 'name', 'coupon_code')->where('id', $id)->first();
            // echo "<br>" . $res->p_id . " : $res->p ,$init";


            $amount = 0;
            if ($init == 1) {
                $amount = 0;
            } elseif ($init == 2) {
                $amount = 0;
            } elseif ($init == 3) {
                $amount = 0;
            } elseif ($init == 4) {
                $amount = 0;
            } elseif ($init == 5) {
                $amount = 0;
            }elseif ($init == 6) {
                $amount = 80;
            }


            if ($amount >= 80) {
                $data = [
                    'node_id' => $res->p,
                    'node_code' => $res->p_id,
                    'node_name' => $res->sponser_name,
                    'coupon' => $cupon_code,
                    'amount' => $amount,
                    'status' => 'credit'
                ];

                DB::table('autotransaction')->insert($data);
            }
            //print_r("test value <pre>".$res);
            self::setAutoParentCommision($res->p, $init, $cupon_code);

        }

    }*/

    public static function getParentList($id, $init = 0)
    {
        $init++;

        if ($id == '') {
            return 1;
        } else {

            $res = DB::table('node')->select('p', 'p_id','name','coupon_code')->where('id', $id)->first();
            echo "<br>" . $res->p_id . " : $res->p ,$init";



            self::getParentList($res->p, $init);

        }
    }

    public static function getTreeStructure($id)
    {
        $res = DB::table('node')->select('name','coupon_code','distributor_id','l','m','r')->where('id', $id)->first();



        $root=new \stdClass();
        $root->id=$id;
        $root->name=$res->name;
        $root->distributor_id=$res->distributor_id;
        $root->coupon_code=$res->coupon_code;

        $resl= DB::table('node')->select('name','coupon_code','distributor_id','l','m','r')->where('id', $res->l)->first();
        if ($resl) {
            $left = new \stdClass();
            $left->id = $res->l;
            $left->name = $resl->name;
            $left->distributor_id = $resl->distributor_id;
            $left->coupon_code = $resl->coupon_code;
        }else
        {
            $left = new \stdClass();
            $left->id = "#";
            $left->name = "Empty";
            $left->distributor_id ="";
            $left->coupon_code = "";
        }

        $resm = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $res->m)->first();
        if ($resm) {
            $middle = new \stdClass();
            $middle->id = $res->m;
            $middle->name = $resm->name;
            $middle->distributor_id = $resm->distributor_id;
            $middle->coupon_code = $resm->coupon_code;

        }else
        {
            $middle = new \stdClass();
            $middle->id = "#";
            $middle->name = "Empty";
            $middle->distributor_id ="";
            $middle->coupon_code = "";
        }


        $resr = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $res->r)->first();
        if ($resr) {
            $right = new \stdClass();
            $right->id = $res->r;
            $right->name = $resr->name;
            $right->distributor_id = $resr->distributor_id;
            $right->coupon_code = $resr->coupon_code;

        }else
        {
            $right = new \stdClass();
            $right->id = "#";
            $right->name = "Empty";
            $right->distributor_id ="";
            $right->coupon_code = "";
        }

        if ($resl)
        {
            $resll = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resl->l)->first();
            if ($resll) {
                $leftl = new \stdClass();
                $leftl->id = $resl->l;
                $leftl->name = $resll->name;
                $leftl->distributor_id = $resll->distributor_id;
                $leftl->coupon_code = $resll->coupon_code;
            } else {
                $leftl = new \stdClass();
                $leftl->id = "#";
                $leftl->name = "Empty";
                $leftl->distributor_id = "";
                $leftl->coupon_code = "";
            }

            $reslm = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resl->m)->first();
            if ($reslm) {
                $leftm = new \stdClass();
                $leftm->id = $resl->m;
                $leftm->name = $reslm->name;
                $leftm->distributor_id = $reslm->distributor_id;
                $leftm->coupon_code = $reslm->coupon_code;
            } else {
                $leftm = new \stdClass();
                $leftm->id = "#";
                $leftm->name = "Empty";
                $leftm->distributor_id = "";
                $leftm->coupon_code = "";
            }

            $reslr = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resl->r)->first();
            if ($reslr) {
                $leftr = new \stdClass();
                $leftr->id = $resl->r;
                $leftr->name = $reslr->name;
                $leftr->distributor_id = $reslr->distributor_id;
                $leftr->coupon_code = $reslr->coupon_code;
            } else {
                $leftr = new \stdClass();
                $leftr->id = "#";
                $leftr->name = "Empty";
                $leftr->distributor_id = "";
                $leftr->coupon_code = "";
            }

        }else
            {
                $leftl = new \stdClass();
                $leftl->id = "#";
                $leftl->name = "Empty";
                $leftl->distributor_id = "";
                $leftl->coupon_code = "";

                $leftm = new \stdClass();
                $leftm->id = "#";
                $leftm->name = "Empty";
                $leftm->distributor_id = "";
                $leftm->coupon_code = "";

                $leftr = new \stdClass();
                $leftr->id = "#";
                $leftr->name = "Empty";
                $leftr->distributor_id = "";
                $leftr->coupon_code = "";


        }



        if ($resm)
        {
            $resml = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resm->l)->first();
            if ($resml) {
                $middlel = new \stdClass();
                $middlel->id = $resm->l;
                $middlel->name = $resml->name;
                $middlel->distributor_id = $resml->distributor_id;
                $middlel->coupon_code = $resml->coupon_code;
            } else {
                $middlel = new \stdClass();
                $middlel->id = "#";
                $middlel->name = "Empty";
                $middlel->distributor_id = "";
                $middlel->coupon_code = "";
            }

            $resmm = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resm->m)->first();
            if ($resmm) {
                $middlem = new \stdClass();
                $middlem->id = $resm->m;
                $middlem->name = $resmm->name;
                $middlem->distributor_id = $resmm->distributor_id;
                $middlem->coupon_code = $resmm->coupon_code;
            } else {
                $middlem = new \stdClass();
                $middlem->id = "#";
                $middlem->name = "Empty";
                $middlem->distributor_id = "";
                $middlem->coupon_code = "";
            }

            $resmr = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resm->r)->first();
            if ($resmr) {
                $middler = new \stdClass();
                $middler->id = $resm->r;
                $middler->name = $resmr->name;
                $middler->distributor_id = $resmr->distributor_id;
                $middler->coupon_code = $resmr->coupon_code;
            } else {
                $middler = new \stdClass();
                $middler->id = "#";
                $middler->name = "Empty";
                $middler->distributor_id = "";
                $middler->coupon_code = "";
            }

        }else
        {
            $middlel = new \stdClass();
            $middlel->id = "#";
            $middlel->name = "Empty";
            $middlel->distributor_id = "";
            $middlel->coupon_code = "";

            $middlem = new \stdClass();
            $middlem->id = "#";
            $middlem->name = "Empty";
            $middlem->distributor_id = "";
            $middlem->coupon_code = "";

            $middler = new \stdClass();
            $middler->id = "#";
            $middler->name = "Empty";
            $middler->distributor_id = "";
            $middler->coupon_code = "";

        }


        if ($resr)
        {
            $resrl = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resr->l)->first();
            if ($resrl) {
                $rightl = new \stdClass();
                $rightl->id = $resr->l;
                $rightl->name = $resrl->name;
                $rightl->distributor_id = $resrl->distributor_id;
                $rightl->coupon_code = $resrl->coupon_code;
            } else {
                $rightl = new \stdClass();
                $rightl->id = "#";
                $rightl->name = "Empty";
                $rightl->distributor_id = "";
                $rightl->coupon_code = "";
            }

            $resrm = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resr->m)->first();
            if ($resrm) {
                $rightm = new \stdClass();
                $rightm->id = $resr->m;
                $rightm->name = $resrm->name;
                $rightm->distributor_id = $resrm->distributor_id;
                $rightm->coupon_code = $resrm->coupon_code;
            } else {
                $rightm = new \stdClass();
                $rightm->id = "#";
                $rightm->name = "Empty";
                $rightm->distributor_id = "";
                $rightm->coupon_code = "";
            }

            $resrr = DB::table('node')->select('name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $resr->r)->first();
            if ($resrr) {
                $rightr = new \stdClass();
                $rightr->id = $resr->r;
                $rightr->name = $resrr->name;
                $rightr->distributor_id = $resrr->distributor_id;
                $rightr->coupon_code = $resrr->coupon_code;
            } else {
                $rightr = new \stdClass();
                $rightr->id = "#";
                $rightr->name = "Empty";
                $rightr->distributor_id = "";
                $rightr->coupon_code = "";
            }


        }else
        {
            $rightl = new \stdClass();
            $rightl->id = "#";
            $rightl->name = "Empty";
            $rightl->distributor_id = "";
            $rightl->coupon_code = "";

            $rightm = new \stdClass();
            $rightm->id = "#";
            $rightm->name = "Empty";
            $rightm->distributor_id = "";
            $rightm->coupon_code = "";

            $rightr = new \stdClass();
            $rightr->id = "#";
            $rightr->name = "Empty";
            $rightr->distributor_id = "";
            $rightr->coupon_code = "";

        }








        $data=['root'=>$root,
                'left'=>$left,
                'middle'=>$middle,
                'right'=>$right,
                'leftl'=>$leftl,
                'leftm'=>$leftm,
                'leftr'=>$leftr,
                'middlel'=>$middlel,
                'middlem'=>$middlem,
                'middler'=>$middler,
                'rightl'=>$rightl,
                'rightm'=>$rightm,
                'rightr'=>$rightr

            ];
        return $data;
    }

    public static function getAutoTree($id)
    {


        $root_data=self::BuidAutoTree($id);
        $left_data=self::BuidAutoTree($root_data['left']->id);
        $middle_data=self::BuidAutoTree($root_data['middle']->id);
        $right_data=self::BuidAutoTree($root_data['right']->id);

        $data=['root'=>$root_data['root'],
            'left'=>$root_data['left'],
            'middle'=>$root_data['middle'],
            'right'=>$root_data['right'],
            'leftl'=>$left_data['left'],
            'leftm'=>$left_data['middle'],
            'leftr'=>$left_data['right'],
            'middlel'=>$middle_data['left'],
            'middlem'=>$middle_data['middle'],
            'middler'=>$middle_data['right'],
            'rightl'=>$right_data['left'],
            'rightm'=>$right_data['middle'],
            'rightr'=>$right_data['right']

        ];
        return $data;

    }
    public static function getSilverTree($id)
    {
        $root_data=self::BuidSilverTree($id);
        $left_data=self::BuidSilverTree($root_data['left']->id);
        $middle_data=self::BuidSilverTree($root_data['middle']->id);
        $right_data=self::BuidSilverTree($root_data['right']->id);

        $data=['root'=>$root_data['root'],
            'left'=>$root_data['left'],
            'middle'=>$root_data['middle'],
            'right'=>$root_data['right'],
            'leftl'=>$left_data['left'],
            'leftm'=>$left_data['middle'],
            'leftr'=>$left_data['right'],
            'middlel'=>$middle_data['left'],
            'middlem'=>$middle_data['middle'],
            'middler'=>$middle_data['right'],
            'rightl'=>$right_data['left'],
            'rightm'=>$right_data['middle'],
            'rightr'=>$right_data['right']

        ];
        return $data;

    }
    public static function getGoldTree($id)
    {


        $root_data=self::BuidGoldTree($id);
        $left_data=self::BuidGoldTree($root_data['left']->id);
        $middle_data=self::BuidGoldTree($root_data['middle']->id);
        $right_data=self::BuidGoldTree($root_data['right']->id);

        $data=['root'=>$root_data['root'],
            'left'=>$root_data['left'],
            'middle'=>$root_data['middle'],
            'right'=>$root_data['right'],
            'leftl'=>$left_data['left'],
            'leftm'=>$left_data['middle'],
            'leftr'=>$left_data['right'],
            'middlel'=>$middle_data['left'],
            'middlem'=>$middle_data['middle'],
            'middler'=>$middle_data['right'],
            'rightl'=>$right_data['left'],
            'rightm'=>$right_data['middle'],
            'rightr'=>$right_data['right']

        ];
        return $data;

    }

    public static function BuidAutoTree($id)
    {
        $res = DB::table('auto_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $id)->first();

        if ($res)
        {
            $root = new \stdClass();
            $root->id = $res->id;
            $root->name = $res->name;
            $root->distributor_id = $res->distributor_id;
            $root->coupon_code = $res->coupon_code;


            $resl = DB::table('auto_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3) - 1))->first();

                if ($resl) {
                    $left = new \stdClass();
                    $left->id = $resl->id;
                    $left->name = $resl->name;
                    $left->distributor_id = $resl->distributor_id;
                    $left->coupon_code = $resl->coupon_code;
                } else {
                    $left = new \stdClass();
                    $left->id = "#";
                    $left->name = "Empty";
                    $left->distributor_id = "";
                    $left->coupon_code = "";
                }



            $resm = DB::table('auto_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3)))->first();

                if ($resm) {
                    $middle = new \stdClass();
                    $middle->id = $resm->id;
                    $middle->name = $resm->name;
                    $middle->distributor_id = $resm->distributor_id;
                    $middle->coupon_code = $resm->coupon_code;
                } else {
                    $middle = new \stdClass();
                    $middle->id = "#";
                    $middle->name = "Empty";
                    $middle->distributor_id = "";
                    $middle->coupon_code = "";
                }




            $resr = DB::table('auto_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3) + 1))->first();


                if ($resr) {
                    $right = new \stdClass();
                    $right->id = $resr->id;
                    $right->name = $resr->name;
                    $right->distributor_id = $resr->distributor_id;
                    $right->coupon_code = $resr->coupon_code;
                } else {
                    $right = new \stdClass();
                    $right->id = "#";
                    $right->name = "Empty";
                    $right->distributor_id = "";
                    $right->coupon_code = "";
                }



        }
        else {
            $root = new \stdClass();
            $root->id = '#';
            $root->name = 'Empty';
            $root->distributor_id = '';
            $root->coupon_code = '';

            $left = new \stdClass();
            $left->id = "#";
            $left->name = "Empty";
            $left->distributor_id = "";
            $left->coupon_code = "";

            $middle = new \stdClass();
            $middle->id = "#";
            $middle->name = "Empty";
            $middle->distributor_id = "";
            $middle->coupon_code = "";

            $right = new \stdClass();
            $right->id = "#";
            $right->name = "Empty";
            $right->distributor_id = "";
            $right->coupon_code = "";
        }


        $data=[
            'root'=>$root,
            'left'=>$left,
            'middle'=>$middle,
            'right'=>$right
            ];

        return $data;

    }
    public static function BuidSilverTree($id)
    {
        $res = DB::table('silver_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $id)->first();

        if ($res)
        {
            $root = new \stdClass();
            $root->id = $res->id;
            $root->name = $res->name;
            $root->distributor_id = $res->distributor_id;
            $root->coupon_code = $res->coupon_code;


            $resl = DB::table('silver_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3) - 1))->first();

            if ($resl) {
                $left = new \stdClass();
                $left->id = $resl->id;
                $left->name = $resl->name;
                $left->distributor_id = $resl->distributor_id;
                $left->coupon_code = $resl->coupon_code;
            } else {
                $left = new \stdClass();
                $left->id = "#";
                $left->name = "Empty";
                $left->distributor_id = "";
                $left->coupon_code = "";
            }



            $resm = DB::table('silver_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3)))->first();

            if ($resm) {
                $middle = new \stdClass();
                $middle->id = $resm->id;
                $middle->name = $resm->name;
                $middle->distributor_id = $resm->distributor_id;
                $middle->coupon_code = $resm->coupon_code;
            } else {
                $middle = new \stdClass();
                $middle->id = "#";
                $middle->name = "Empty";
                $middle->distributor_id = "";
                $middle->coupon_code = "";
            }




            $resr = DB::table('silver_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3) + 1))->first();


            if ($resr) {
                $right = new \stdClass();
                $right->id = $resr->id;
                $right->name = $resr->name;
                $right->distributor_id = $resr->distributor_id;
                $right->coupon_code = $resr->coupon_code;
            } else {
                $right = new \stdClass();
                $right->id = "#";
                $right->name = "Empty";
                $right->distributor_id = "";
                $right->coupon_code = "";
            }



        }
        else {
            $root = new \stdClass();
            $root->id = '#';
            $root->name = 'Empty';
            $root->distributor_id = '';
            $root->coupon_code = '';

            $left = new \stdClass();
            $left->id = "#";
            $left->name = "Empty";
            $left->distributor_id = "";
            $left->coupon_code = "";

            $middle = new \stdClass();
            $middle->id = "#";
            $middle->name = "Empty";
            $middle->distributor_id = "";
            $middle->coupon_code = "";

            $right = new \stdClass();
            $right->id = "#";
            $right->name = "Empty";
            $right->distributor_id = "";
            $right->coupon_code = "";
        }


        $data=[
            'root'=>$root,
            'left'=>$left,
            'middle'=>$middle,
            'right'=>$right
        ];

        return $data;

    }
    public static function BuidGoldTree($id)
    {
        $res = DB::table('gold_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', $id)->first();

        if ($res)
        {
            $root = new \stdClass();
            $root->id = $res->id;
            $root->name = $res->name;
            $root->distributor_id = $res->distributor_id;
            $root->coupon_code = $res->coupon_code;


            $resl = DB::table('gold_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3) - 1))->first();

            if ($resl) {
                $left = new \stdClass();
                $left->id = $resl->id;
                $left->name = $resl->name;
                $left->distributor_id = $resl->distributor_id;
                $left->coupon_code = $resl->coupon_code;
            } else {
                $left = new \stdClass();
                $left->id = "#";
                $left->name = "Empty";
                $left->distributor_id = "";
                $left->coupon_code = "";
            }



            $resm = DB::table('gold_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3)))->first();

            if ($resm) {
                $middle = new \stdClass();
                $middle->id = $resm->id;
                $middle->name = $resm->name;
                $middle->distributor_id = $resm->distributor_id;
                $middle->coupon_code = $resm->coupon_code;
            } else {
                $middle = new \stdClass();
                $middle->id = "#";
                $middle->name = "Empty";
                $middle->distributor_id = "";
                $middle->coupon_code = "";
            }




            $resr = DB::table('gold_node')->select('id', 'name', 'coupon_code', 'distributor_id', 'l', 'm', 'r')->where('id', (($id * 3) + 1))->first();


            if ($resr) {
                $right = new \stdClass();
                $right->id = $resr->id;
                $right->name = $resr->name;
                $right->distributor_id = $resr->distributor_id;
                $right->coupon_code = $resr->coupon_code;
            } else {
                $right = new \stdClass();
                $right->id = "#";
                $right->name = "Empty";
                $right->distributor_id = "";
                $right->coupon_code = "";
            }



        }
        else {
            $root = new \stdClass();
            $root->id = '#';
            $root->name = 'Empty';
            $root->distributor_id = '';
            $root->coupon_code = '';

            $left = new \stdClass();
            $left->id = "#";
            $left->name = "Empty";
            $left->distributor_id = "";
            $left->coupon_code = "";

            $middle = new \stdClass();
            $middle->id = "#";
            $middle->name = "Empty";
            $middle->distributor_id = "";
            $middle->coupon_code = "";

            $right = new \stdClass();
            $right->id = "#";
            $right->name = "Empty";
            $right->distributor_id = "";
            $right->coupon_code = "";
        }


        $data=[
            'root'=>$root,
            'left'=>$left,
            'middle'=>$middle,
            'right'=>$right
        ];

        return $data;

    }

    public static function AsignAutoNodeParrent($id)
    {

        $l=$id*3-1;
        $m=$id*3;
        $r=$id*3+1;
        $p=round($id/3);

        $data = [
            'p'=>$p,
            'l'=>$l,
            'm'=>$m,
            'r'=>$r

        ];
        $res = DB::table('auto_node')->where('id',$id )->update($data);

        if ($res == 1) {
            return 1;

        } else {
            return 0;
        }
    }
    public static function AsignSilverNodeParrent($id)
    {

        $l=$id*3-1;
        $m=$id*3;
        $r=$id*3+1;
        $p=round($id/3);

        $data = [
            'p'=>$p,
            'l'=>$l,
            'm'=>$m,
            'r'=>$r

        ];
        $res = DB::table('silver_node')->where('id',$id )->update($data);

        if ($res == 1) {
            return 1;

        } else {
            return 0;
        }
    }
    public static function AsignGoldNodeParrent($id)
    {

        $l=$id*3-1;
        $m=$id*3;
        $r=$id*3+1;
        $p=round($id/3);

        $data = [
            'p'=>$p,
            'l'=>$l,
            'm'=>$m,
            'r'=>$r

        ];
        $res = DB::table('gold_node')->where('id',$id )->update($data);

        if ($res == 1) {
            return 1;

        } else {
            return 0;
        }
    }

    /*public static function setAutoNodeParentCommision($id, $init = 0,$cupon_code=0)
    {
        $init++;

        if ($id == '') {
            return 1;
        }
        else
            {

            $res = DB::table('node')->select('id', 'distributor_id', 'sponser_name', 'p', 'p_id', 'name', 'coupon_code')->where('id', $id)->first();

                if ($res) {
                    if ($res->p >= 1)
                    {
                        $amount = 0;
                        if ($init == 1) {
                            $amount = 300;
                        } elseif ($init == 2) {
                            $amount = 200;
                        } elseif ($init == 3) {
                            $amount = 100;
                        } elseif ($init == 4) {
                            $amount = 50;
                        } elseif ($init == 5) {
                            $amount = 20;
                        }


                        if ($amount >= 20) {
                            $data = [
                                'node_id' => $res->p,
                                'node_code' => $res->p_id,
                                'node_name' => $res->sponser_name,
                                'coupon' => $cupon_code,
                                'amount' => $amount,
                                'status' => 'credit'
                            ];

                           // DB::table('transaction')->insert($data);
                            echo "<pre>";
                            echo "<br>" . $res->p_id . " : $res->p ,$init  amount $amount";
                            print_r($data);
                            echo "<br>";

                        }
                        //print_r("test value <pre>".$res);
                        self::setAutoNodeParentCommision($res->p, $init, $cupon_code);
                    }


                }
        }


    }*/

    public function GetTreeCompleteNodeValue($parentNode,$mode,$height)
    {
        return $parentNode*(pow($mode,$height)) +((pow($mode,$height)-1)/($mode-1));
    }


    public function FillAutoNodeTreeTable($treeLevel)
    {
        for ($i = 1; $i <= $treeLevel; $i++) {
            echo $i . "  : " . $this->GetTreeCompleteNodeValue($i, 3, 6) . "<br>";
            {
                $data = [
                    'node' => $i,
                    'value' => $this->GetTreeCompleteNodeValue($i, 3, 6)
                ];
                DB::table('auto_tree_value')->insert($data);
            }
        }
    }

    public function FillSilverNodeTreeTable($treeLevel)
    {
        for ($i = 1; $i <= $treeLevel; $i++) {
            echo $i . "  Silver Level value : " . $this->GetTreeCompleteNodeValue($i, 3, 3) . "<br>";
            {
                $data = [
                    'node' => $i,
                    'value' => $this->GetTreeCompleteNodeValue($i, 3, 3)
                ];
                DB::table('silver_tree_value')->insert($data);
            }
        }
    }

    public function FillGoldNodeTreeTable($treeLevel)
    {
        for ($i = 1; $i <= $treeLevel; $i++) {
            echo $i . " Gold Level value : " . $this->GetTreeCompleteNodeValue($i, 3, 2) . "<br>";
            {
                $data = [
                    'node' => $i,
                    'value' => $this->GetTreeCompleteNodeValue($i, 3, 2)
                ];
                DB::table('gold_tree_value')->insert($data);
            }
        }
    }

    public  function AutoNodePayoutEligibulity($id)
    {
        $res = DB::table('auto_tree_value')->select('id', 'node')->where('value', $id)->first();
        if($res)
        {

            //return $res->node;
           $this->AutoNodePayout($res->node);
        }
    }

    public function SilverNodePayoutEligibulity($id)
    {
        $res = DB::table('silver_tree_value')->select('id', 'node')->where('value', $id)->first();
        if($res)
        {
            //return $res->node;
            $this->SilverNodePayout($res->node);
        }
    }
    public function GoldNodePayoutEligibulity($id)
    {
        $res = DB::table('gold_tree_value')->select('id', 'node')->where('value', $id)->first();
        if($res)
        {
            //return $res->node;
            $this->GoldNodePayout($res->node);
        }
    }

    public function AutoNodePayout($id)
    {

        $res=DB::table('auto_node')->where('id', $id)->first();
        if($res)
        {
            $node_code=$res->distributor_id;
            $node_name=$res->name;
            $coupon=$res->coupon_code;



            $fulldata=json_decode(json_encode($res),true); // convert object to array
            unset($fulldata['id'],$fulldata['cdate']);   // to remove unwanted key fields like id and cdate
            $id=DB::table('silver_node')->insertGetId($fulldata) ;

            self::AsignSilverNodeParrent($id);

            $data=[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>$coupon,
                'amount'=>58320,
                'status'=>'credit'
            ];

            $dataSilvarId=[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>'SILVER-ID-COST',
                'amount'=>4000,
                'status'=>'debit'
            ];
            $dataSilvarReg =[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>'SILVER-REG-FEE',
                'amount'=>4320,
                'status'=>'debit'
            ];

            DB::table('auto_transaction')->insert($data);
            DB::table('auto_transaction')->insert($dataSilvarId);
            DB::table('auto_transaction')->insert($dataSilvarReg);

            //return $id;
            $this->SilverNodePayoutEligibulity($id);
        }


    }
    public function SilverNodePayout($id)
    {

        $res=DB::table('silver_node')->where('id', $id)->first();
        if($res)
        {
            $node_code=$res->distributor_id;
            $node_name=$res->name;
            $coupon=$res->coupon_code;



            $fulldata=json_decode(json_encode($res),true); // convert object to array
            unset($fulldata['id'],$fulldata['cdate']);   // to remove unwanted key fields like id and cdate
            $id=DB::table('gold_node')->insertGetId($fulldata) ;
            self::AsignGoldNodeParrent($id);
            $data=[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>$coupon,
                'amount'=>108000,
                'status'=>'credit'
            ];

            $dataGoldId=[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>'GOLD-ID-COST',
                'amount'=>8000,
                'status'=>'debit'
            ];
            $dataGoldReg =[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>'GOLD-REG-FEE',
                'amount'=>2000,
                'status'=>'debit'
            ];

            DB::table('silver_transaction')->insert($data);
            DB::table('silver_transaction')->insert($dataGoldId);
            DB::table('silver_transaction')->insert($dataGoldReg);

            //return $id;
            $this->GoldNodePayoutEligibulity($id);
        }


    }
    public function GoldNodePayout($id)
    {

        $res=DB::table('gold_node')->where('id', $id)->first();
        if($res)
        {
            $node_code=$res->distributor_id;
            $node_name=$res->name;
            $coupon=$res->coupon_code;



            //$fulldata=json_decode(json_encode($res),true); // convert object to array
            //unset($fulldata['id'],$fulldata['cdate']);   // to remove unwanted key fields like id and cdate
            //$id=DB::table('gold_node')->insertGetId($fulldata) ;

            $data=[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>$coupon,
                'amount'=>72000,
                'status'=>'credit'
            ];
/*
            $dataGoldId=[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>'GOLD-ID-COST',
                'amount'=>8000,
                'status'=>'debit'
            ];
            $dataGoldReg =[
                'node_id'=>$id,
                'node_code'=>$node_code,
                'node_name'=>$node_name,
                'coupon'=>'GOLD-REG-FEE',
                'amount'=>2000,
                'status'=>'debit'
            ];*/

            DB::table('gold_transaction')->insert($data);
           /* DB::table('silver_transaction')->insert($dataGoldId);
            DB::table('silver_transaction')->insert($dataGoldReg);*/

        }


    }


}
