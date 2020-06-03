<?php

namespace App\Http\Controllers;

use App\Node;
use Illuminate\Http\Request;

class TreeController extends Controller
{
    //
    public function index(){
        $id=\request()->id;
     $data=   Node::getTreeStructure($id);

/*     echo "<pre>";
     print_r($data);*/
        return view('dashboard.tree.index')->with($data);
    }


    public function AutoTree(){
        $id=\request()->id;
        $data=   Node::getAutoTree($id);

        /*     echo "<pre>";
             print_r($data);*/
        return view('dashboard.tree.auto_tree')->with($data);
    }

    public function GoldTree(){
        $id=\request()->id;
        $data=   Node::getGoldTree($id);

        /*     echo "<pre>";
             print_r($data);*/
        return view('dashboard.tree.gold_tree')->with($data);
    }

    public function SilverTree(){
        $id=\request()->id;
        $data=   Node::getSilverTree($id);

        /*     echo "<pre>";
             print_r($data);*/
        return view('dashboard.tree.silver_tree')->with($data);
    }


}
