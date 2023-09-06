<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\balance;
use App\Models\transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function createTransaction(Request $request){
        $validator = Validator::make($request->all(), [
            'amount' => array('required', 'regex:/^\d*(\.\d{2})?$/')

        ]);

        // generate id_trx
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = substr(str_shuffle($characters), 0, 6);

            // check validatasi
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user = Auth::user();
        $getDataBalance = balance::where('user_id',$user->id)->get()->toArray();

        // validasi amount_available < input amount
        if($getDataBalance[0]['amount_available'] < $request->amount){
            return response()->json( [
                'status'   => 400,
                'message'  => 'input amount labih besar dari amount available',
            ] );
        }

        // save ke table transaction
        $saveData = new transaction();
        $saveData->trx_id=$randomString;
        $saveData->user_id=$user->id;
        $saveData->amount=$request->amount;
        $saveData->save();

        if($saveData){
            // slep
            sleep(30);

            // update amount available
            $update = balance::find($getDataBalance[0]['id']);
            $update->amount_available=$request->amount;
            $update->save();

            if($update){
                return response()->json( [
                    'status'   => 200,
                    'trx_id'   => $randomString,
                    'amount'   =>$request->amount
                ] );
            }
        }

       

    }

    public function getTransaction(Request $request){
        // get data user
        $getDataUser = User::get()->toArray();
      
            $data_=[];
            foreach ($getDataUser as $user) {
                // get data balance
                $getDataBalance= balance::where('user_id',$user['id'])->get();
                
                //get data transaction
                $getDataTrans= transaction::where('user_id',$user['id'])->select('trx_id', 'amount')->get();

                //generate format response    
                $data[]=[
                    'user_id'=>$user['id'],
                    'user_name' =>$user['name'],
                    'balance' =>$getDataBalance[0]->amount_available,
                    'transaction'=>$getDataTrans,
                ];   
            };
            return response()->json( [
                'status' =>200,
                'data'  => $data,
            ] );

    }
}
