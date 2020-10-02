<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use App\Game;
use App\Army;
use App\Pivot;
use App\Strategy;

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

            //$game = Game::find(intval($request->gameId));
            $game = Game::where("id", "=", $request->gameId)->with("armies.strategy")->get()[0];
            $ifArmyExists = Army::where("name", "=", $request->armyName)->first();

            if($ifArmyExists === null){

                //if(count($game->armies) < 2){

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
                        "armyid" => $army->id,
                        "game" => $game
                    );
                    
                    return response()->json($response);

                /*}
                else{

                    $response = array(
                        "message" => "To much armies",
                        "requestAll" => $request->all(),
                    );

                    return response()->json($response);

                }*/

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

        $validator = Validator::make($request->all(), [ // <---
            "armyId" => 'required',
            "strategy" => 'required|integer|between:1,3',
        ]);

        if($validator->fails()){
 
            $response = array(
                "Errors: " => $validator->failed(),
            );
            
            return response()->json($response); 

        }
        else{

            $tmp1 = Strategy::where("army_id", "=", intval($request->armyId))->first();
            $newStrategy = null;
            $armyId = $request->armyId;
            $strategy = $request->strategy;
            $message = "";
            if($tmp1){

                $newStrategy = $tmp1;
                $newStrategy->strategy = intval($strategy);
                $newStrategy->save();
                $message = "Strategy is changed to: ";

            }
            else{

                $newStrategy = new Strategy;
                $newStrategy->army_id = intval($armyId);
                $newStrategy->strategy = intval($strategy);
                $newStrategy->save();
                $message = "New Strategy is added: ";

            }
            
            $response = array(
                "message" => $message,
                "newStrategy" => $newStrategy,
            );
            
            return response()->json($response);

        }

    }

    public function listArmies(Request $request)
    {
        $army = Army::with("strategy")->get();

        $response = array(
            "army" => $army,
        );
        
        return response()->json($response);

    }

    public function listGames(Request $request)
    {
        $games = Game::with("armies.strategy")->get();
        $response = array(
            "games" => $games,
        );
        
        return response()->json($response);

    }

    public function commenceBattle(Request $request){

        $game = Game::where("id", "=", $request->gameId)->with("armies.strategy")->get();
        
        $armies = $game[0]->armies;
        $len = count($armies);

        if($len > 0){
        
            $units1 = [];
            for($i=0;$i<$len;$i++){

                $units1[$i] = $armies[$i]->units;

            }

            $strongest = max($units1);
            $strongInd = null;
            $weakest = min($units1);
            $weakInd = null;
            for($i=0;$i<$len;$i++){

                if($strongest === $armies[$i]->units){
                    $strongInd = $i;
                }

                if($weakest === $armies[$i]->units){
                    $weakInd = $i;
                }

            }
            $randArmy = rand(0, $len-1);
            for($i=0;$i<$len;$i++){

                $ifStrategyExists = $armies[$i]->strategy ? $armies[$i]->strategy->strategy : 1;
                //Random: 1
                if($ifStrategyExists === 1 || $armies[$i]->units > 0){

                    while($armies[$i]->units > 0 && $armies[$randArmy]->units > 0){

                        $Army1 = rand(0, $armies[$i]->units) / 100;
                        $Army2 = rand(0, $armies[$randArmy]->units) / 100;
        
                        if($Army1 > $Army2){

                            $armies[$randArmy]->units = $armies[$randArmy]->units > 1 ? $armies[$randArmy]->units-0.5 : $armies[$randArmy]->units-1;
                        
                        }
            
                        if($Army1 < $Army2){

                            $armies[$i]->units = $armies[$i]->units > 1 ? $armies[$i]->units-0.5 : $armies[$i]->units-1;
                            
                        }
        
                    }

                }

                //Weakest: 2
                if($ifStrategyExists === 2 && $armies[$i]->units > 0){

                    while($armies[$i]->units > 0 && $armies[$weakInd]->units > 0){

                        $Army1 = rand(0, $armies[$i]->units) / 100;
                        $Army2 = rand(0, $armies[$weakInd]->units) / 100;
        
                        if($Army1 > $Army2){

                            $armies[$weakInd]->units = $armies[$weakInd]->units > 1 ? $armies[$weakInd]->units-0.5 : $armies[$weakInd]->units-1;
                        
                        }
            
                        if($Army1 < $Army2){

                            $armies[$i]->units = $armies[$i]->units > 1 ? $armies[$i]->units-0.5 : $armies[$i]->units-1;
                            
                        }
        
                    }

                }

                //Strongest: 3
                if($ifStrategyExists === 3 && $armies[$i]->units > 0){

                    while($armies[$i]->units > 0 && $armies[$strongInd]->units > 0){

                        $Army1 = rand(0, $armies[$i]->units) / 100;
                        $Army2 = rand(0, $armies[$strongInd]->units) / 100;
        
                        if($Army1 > $Army2){

                            $armies[$strongInd]->units = $armies[$strongInd]->units > 1 ? $armies[$strongInd]->units-0.5 : $armies[$strongInd]->units-1;
                        
                        }
            
                        if($Army1 < $Army2){

                            $armies[$i]->units = $armies[$i]->units > 1 ? $armies[$i]->units-0.5 : $armies[$i]->units-1;
                            
                        }
        
                    }

                }

            }

            $winner = "";
            for($i=0;$i<$len;$i++){

                if($armies[$i]->units > 0){
                    $winner .= $armies[$i]->name.",";
                }

            }

            $response = array(
                "message" => "Attack",
                "armies" => $armies,
                "winner" => $winner
            );
            
            return response()->json($response);

        }
        else{

            $response = array(
                "message" => "There are no armies in this game!",
            );
            
            return response()->json($response);

        }

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
