<?php

namespace App\Http\Controllers;

use App\Models\FavouriteTeam;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FavouriteController extends ApiController
{
    public function index(Request $request){
        $favourites=FavouriteTeam::where('user_id',$request->user_id)->get();

        return $this->respondWithSuccess(
            'Your favourite list retrieved successfully',
             $favourites
         );

    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [

            'user_id' => 'required',
            'team_id' => 'required',

        ]);


        if($validator->fails()){
            return $this->respondWithError(
                'Validation Error',
                $validator->errors(),
                422
            );
        }

        $message='Team added to your favourite list';
        $favourite=FavouriteTeam::where('user_id', $request->user_id)
                      ->where('team_id', $request->team_id);



        try {

            if($favourite->exists()){
                $favourite->first()->delete();
                $message='Team removed from your favourite list';

            }else{
                FavouriteTeam::create([
                    'user_id'=> $request->user_id,
                    'team_id'=> $request-> team_id,

                ]);
            }

            return $this->respondWithSuccess(
               $message
                // $request->all()
            );

        } catch (Exception $e) {

            Log::info($e->getMessage());

            return $this->respondWithError(
                'Something is wrong. Try again.',
                $e->getMessage(),
                500
            );
        }


    }
}
