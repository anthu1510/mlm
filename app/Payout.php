<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payout extends Model
{
    //
    public static function InsertMaster($data)
    {
        return DB::table('payout_master')->insertGetId($data);

    }
    public static function InsertDeatil($payoutid,$padate,$data)
    {
        $fulldata=json_decode(json_encode($data),true); // convert object to array


        foreach ($fulldata as $dataPart)
        {
            $dataPart['payout_id'] = $payoutid;
            $dataPart['pdate'] = $padate;
            $detailData[] = $dataPart;
        }



        $res= DB::table('payout_detail')->insert($detailData);

        if($res)
        {
            return 1;
        }
    }
}
