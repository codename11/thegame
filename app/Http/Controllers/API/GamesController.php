<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use App\Game;
use App\Army;
use App\Pivot;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function setupScreen()
    {

        return view("setupScreen");

    }

    public function battlefield()
    {

        return view("battlefield");

    }
    
    public function createGame(Request $request)
    {

        $validator = Validator::make($request->all(), [ // <---
            "gameName" => 'required|min:4|max:255',
        ]);

        if($validator->fails()){
 
            $response = array(
                "Errors: " => $validator->failed(),
            );
            
            return response()->json($response); 

        }
        else{

            $game = new Game;
            $game->name = $request->gameName;
            $game->save();

            $response = array(
                "message" => "Game has been created.",
                "gameId" => $game->id,
                "gameName" => $game->name
            );
            
            return response()->json($response); 

        }
        

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addArmy(Request $request)
    {

        $validator = Validator::make($request->all(), [ // <---
            "armyName" => 'required|min:4|max:255',
            "unitsNumber" => 'required|integer|between:0,100',
        ]);

        if($validator->fails()){
 
            $response = array(
                "Errors: " => $validator->failed(),
            );
            
            return response()->json($response); 

        }
        else{

            $game = Game::find(intval($request->gameId));

            $ifArmyExists = Army::where("name", "=", $request->armyName)->first();

            if($ifArmyExists === null){

                $army = new Army;
                $army->name = $request->armyName;
                $army->units = intval($request->unitsNumber);
                $army->save();

                $pivot = new Pivot;
                $pivot->army_id = $army->id;
                $pivot->game_id = $game->id;
                $pivot->save();
                
                $response = array(
                    "message" => "Bravo",
                    "requestAll" => $request->all(),
                );
                
                return response()->json($response);

            }
            else{

                $response = array(
                    "Errors" => "Record exists",
                );
                
                return response()->json($response);

            }
            

        }

    }

    public function attackStrategy(Request $request)
    {

        $armyId = $request->armyId;
        $strategy = $request->strategy;

        $minId = Army::min('id');
        $maxId = Army::max('id');

        $randId = rand($minId,$maxId)!== $armyId ? rand($minId,$maxId) : $maxId;
        $randArmy = Army::find($randId);

        $ThisArmy = Army::find($armyId);

        $strongestArmy = Army::orderBy('units', 'desc')->where("id", "!=", $armyId)->first();
        $weakestArmy = Army::orderBy('units', 'asc')->where("id", "!=", $armyId)->first();

        $ArmyToAttack = null;
        switch($strategy) {
            case 1:
              
                $ArmyToAttack = $randArmy;

            break;

            case 2:
              
                $ArmyToAttack = $weakestArmy;

            break;

            case 3:
              
                $ArmyToAttack = $strongestArmy;

            break;
            
            default:
                $ArmyToAttack = null;
        }

        /*
        $ThisArmy
        $ArmyToAttack
        $attack = rand($ThisArmy->pluck("units"),$ArmyToAttack->pluck("units"));
        */

        $units1 = $ThisArmy->units;
        $units2 = $ArmyToAttack->units;

        $a = $units1;
        $b = $units2;

        $percent = rand(0, 100);
        $theWinner = "";
        while($units1>0 || $units2>0){

            $Army1 = rand(0, $a) / 100;
            $Army2 = rand(0, $b) / 100;

            if($Army1 > $Army2){
                $units2 = $units2 > 1 ? $units2-0.5 : $units2-1;
            }

            if($Army1 < $Army2){
                $units1 = $units1 > 1 ? $units1-0.5 : $units1-1;
            }

        }

        if($units1<=0){

            $theWinner = $ThisArmy->name;

        }

        if($units2<=0){

            $theWinner = $ArmyToAttack->name === $ThisArmy->name ? $ArmyToAttack->name."(copy)" : $ArmyToAttack->name;

        }

        $response = array(
            "strategy" => $strategy,
            "AttackingArmy" => $ThisArmy,
            "ArmyToAttack" => $ArmyToAttack,
            "Winner" => $theWinner,
            "units1" => $units1,
            "units2" => $units2
        );
        
        return response()->json($response);

    }

    public function listArmies(Request $request)
    {
        $army = Army::all();

        $response = array(
            "army" => $army,
        );
        
        return response()->json($response);

    }

    public function listGames(Request $request)
    {
        $games = Game::with("armies")->get();

        $response = array(
            "games" => $games,
        );
        
        return response()->json($response);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
