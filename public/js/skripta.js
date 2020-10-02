let token = null;

function register(event){
        
    if(event){
        event.preventDefault();
    }

    let url = "/api/register";

    let data = {
        name: "petar",
        email: "petrovic@gmail.com",
        password: "tttttttt",
        password_confirmation: "tttttttt",
    };

    $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "JSON",

        success: (response) => { 

            console.log("success");
            console.log(response);  

            document.getElementById("registered").innerHTML = "<div id='toast3' class='toast myToast' data-autohide='true' data-delay='2000'><div class='toast-header'><strong class='mr-auto text-primary'>Strategy</strong><strong class='text-muted'></strong><button type='button' class='ml-2 mb-1 close' data-dismiss='toast'>&times;</button></div><div class='toast-body'>You are now registered!</div></div>";
            $('#toast3').toast('show');
            
        },
        error: (response) => {

            console.log("error");
            console.log(response);
            
        }

    });

}

function login(event){

    if(event){
        event.preventDefault();
    }

    let url = "/api/login";

    let data = {
        email: "petrovic@gmail.com",
        password: "tttttttt"
    };
    
    $.ajax({
        url: url,
        type: "GET",
        data: data,
        dataType: "JSON",

        success: (response) => { 

            //console.clear();;
            console.log("success");
            console.log(response);  
            token = response.access_token;
            listGames();

        },
        error: (response) => {

            console.log("error");
            console.log(response);
            
        }

    });

}

async function createGame(event){

    event.preventDefault();
    let forma = event.target;
 
    let data = {
        gameName: forma.elements[0].value
    };

    let url = "/api/createGame";
    await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        headers: {
            "Accepts" : "application/json",
            'Content-Type': 'application/json',
            "Authorization": "Bearer "+token,
        },
        body: JSON.stringify(data)
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        //console.clear();;
        console.log(data);
        forma.elements[0].value = null;
        listGames();

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

async function listGames(event){

    if(event){
        event.preventDefault();
    }

    if(token && token.length > 0){

        let url = "/api/listGames";
        await fetch(url, {
            method: 'GET', // *GET, POST, PUT, DELETE, etc.
            headers: {
                "Accepts" : "application/json",
                'Content-Type': 'application/json',
                "Authorization": "Bearer "+token,
            },
        })
        .then((response) => {

            return response.json();

        })// parses JSON response into native JavaScript objects
        .then((data) => {

            //console.clear();;
            console.log(data);
            let games = data.games;

            let href = window.location.href;
            let pos1 = href.lastIndexOf("/");
            let route = href.substr(pos1);

            if(route === "/setupScreen"){

                displayGames(games);

            }

            if(route === "/battlefield"){
                
                goToBattlefield(games);

            }
            
        })
        .catch((error) => {

            console.error('Error:', error);

        });

    }

}

function goToBattlefield(games){

    let len = games.length;

    if(len>0){

        let listGames = "<h3>Select a Game and let battle commence!</h3><form onsubmit='commenceBattle(event)'><div class='form-group'><label for='gamelist'>Select game:</label><select class='form-control' id='gamelist' name='gamelist'>";
    
        for(let i=0;i<len;i++){

            listGames += "<option value="+games[i].id+">"+games[i].name+"</option>"

        }
        listGames += "</select></div><div class='form-group'><button type='submit' class='btn btn-outline-primary'>Commence Battle</button></div></form>";
        document.getElementById("listGames").innerHTML = listGames;

    }

}

async function commenceBattle(event){

    event.preventDefault();
    let forma = event.target;
    
    document.getElementById("winner").classList.remove("animate__animated", "animate__heartBeat");
    let gameId = forma.elements[0].value;

    let data = {
        gameId: gameId,
    };

    let url = "/api/commenceBattle";
    await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        headers: {
            "Accepts" : "application/json",
            'Content-Type': 'application/json',
            "Authorization": "Bearer "+token,
        },
        body: JSON.stringify(data)
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {
        //console.clear();;
        console.log(data);

        let message = data.message;
        if(message && message==="There are no armies in this game!"){
            
            document.getElementById("noArmies").innerHTML = "<div id='toast2' class='toast myToast' data-autohide='true' data-delay='2000'><div class='toast-header'><strong class='mr-auto text-primary'>Strategy</strong><strong class='text-muted'></strong><button type='button' class='ml-2 mb-1 close' data-dismiss='toast'>&times;</button></div><div class='toast-body'>"+message+"</div></div>";
            $('#toast2').toast('show');
            document.getElementById("winner").innerHTML = "";
            
        }
        else{

            document.getElementById("battleSounds").play();

            let winner = data.winner.split(",").filter((item, i) => {
                return item!="";
            });

            let len = winner.length;
            document.getElementById("winner").innerHTML = "";
            document.getElementById("winner").classList.add("animate__animated", "animate__heartBeat");
            if(len > 1){

                document.getElementById("winner").innerHTML = "<h3 class='alert alert-info'>We have multiple surviving armies: </h3><br/>";

            }

            if(len === 1){

                document.getElementById("winner").innerHTML = "<h3 class='alert alert-info'>We have a winner army: </h3><br/>";

            }

            if(len === 0){

                document.getElementById("winner").innerHTML = "<h3 class='alert alert-info'>We don't have a winner, it's a draw. </h3><br/>";

            }

            for(let i=0;i<len;i++){

                document.getElementById("winner").innerHTML += "<span class='alert alert-warning'>"+winner[i]+"</span>";

                if(i!=len-1){

                    document.getElementById("winner").innerHTML += " with ";
                    
                }
                
            }
            
            setTimeout(() => {
                document.getElementById("battleSounds").pause();
                document.getElementById("battleSounds").currentTime = 0;
            }, 3015);

        }       

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

function displayGames(games){

    let len = games.length;

    if(len>0){

        let listGames = "<h3>Select a game and add a army</h3><form onsubmit='addArmy(event)'><div class='form-group'><label for='gamelist'>Select game:</label><select class='form-control' id='gamelist' name='gamelist'>";
    
        for(let i=0;i<len;i++){

            listGames += "<option value="+games[i].id+">"+games[i].name+"</option>"

        }
        let armyName = "<div class='form-group'><label for='armyName'>ArmyName:</label><input type='text' class='form-control' placeholder='Enter Army Name' id='armyName' name='armyName'></div>";
        let unitsNumber = "<div class='form-group'><label for='unitsNumber'>Number of Units:</label><input type='number' min='0' max='100' class='form-control' placeholder='Enter number of units' id='unitsNumber' name='unitsNumber'></div>";
        listGames += "</select></div>"+armyName+unitsNumber+"<div class='form-group'><button type='submit' class='btn btn-outline-primary'>Add Army</button></div></form>";
        document.getElementById("listGames").innerHTML = listGames;

    }
    else{
        document.getElementById("listGames").innerHTML = "<h4>No games? No armies ...</h4>";
    }

}

async function addArmy(event){

    event.preventDefault();
    let forma = event.target;
    
    let gameId = forma.elements[0].value;
    let armyName = forma.elements[1].value;
    let unitsNumber = forma.elements[2].value;

    let data = {
        gameId: gameId,
        armyName: armyName,
        unitsNumber: unitsNumber
    };

    let url = "/api/addArmy";
    await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        headers: {
            "Accepts" : "application/json",
            'Content-Type': 'application/json',
            "Authorization": "Bearer "+token,
        },
        body: JSON.stringify(data)
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        //console.clear();;
        console.log(data);
        forma.elements[0].value = gameId;
        forma.elements[1].value = null;
        forma.elements[2].value = null;
        listArmies();

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

async function listArmies(){

    let url = "/api/listArmies";
    await fetch(url, {
        method: 'GET', // *GET, POST, PUT, DELETE, etc.
        headers: {
            "Accepts" : "application/json",
            'Content-Type': 'application/json',
            "Authorization": "Bearer "+token,
        },
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        //console.clear();;
        console.log(data);
        let armies = data.army;
        let len = armies.length;

        let html = "";
        if(len>0){

            let attackingArmies = "<h3>Select a army and add a strategy</h3><form onsubmit='setStrategy(event)'><div class='form-group'><label for='armylist'>Select army:</label><select class='form-control' id='armylist' name='armylist'>";
            
            for(let i=0;i<len;i++){

                attackingArmies += "<option value="+armies[i].id+">"+armies[i].name+"</option>"

            }
            attackingArmies += "</select></div>";

            let strategies = "<div class='form-group'><label for='strategy'>Strategies:</label><select class='form-control' id='strategy' name='strategy'><option value='1'>Random</option><option value='2'>Weakest</option><option value='3'>Strongest</option></select></div>";

            html += attackingArmies + strategies + "<div class='form-group'><button type='submit' class='btn btn-outline-primary'>Set Attack Strategy</button></div></form>";
            document.getElementById("attackStrategy").innerHTML = html;

        }

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

async function setStrategy(event){

    event.preventDefault();
    let forma = event.target;

    let armyId = forma.elements[0].value;
    let strategy = forma.elements[1].value;

    let data = {
        armyId: armyId,
        strategy: strategy,
    };
   
    let url = "/api/attackStrategy";
    await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        headers: {
            "Accepts" : "application/json",
            'Content-Type': 'application/json',
            "Authorization": "Bearer "+token,
        },
        body: JSON.stringify(data)
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        console.log(data);

        let strId = data.newStrategy.strategy;
        let newStrategy = "";
        let message = data.message;

        if(strId===1){
            newStrategy = "Random";
        }

        if(strId===2){
            newStrategy = "Weakest";
        }

        if(strId===3){
            newStrategy = "Strongest";
        }

        document.getElementById("strategyApplied").innerHTML = "<div id='toast1' class='toast myToast' data-autohide='true' data-delay='2000'><div class='toast-header'><strong class='mr-auto text-primary'>Strategy</strong><strong class='text-muted'></strong><button type='button' class='ml-2 mb-1 close' data-dismiss='toast'>&times;</button></div><div class='toast-body'>"+message+newStrategy+"</div></div>";
        $('#toast1').toast('show');

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

function setupScreen(){

    let html ="<div class='container'><h3>setupScreen or <a type='button' href='/api/battlefield' class='btn btn-outline-danger' style='margin-left: 10px;'>Go to Battlefield</a></h3><form onsubmit='createGame(event)'><div class='form-group'><label for='gameName'>Game Name:</label><input type='text' class='form-control' placeholder='Enter name for the Game' name='gameName' id='gameName' required></div><div class='form-group'><button type='submit' class='btn btn-outline-primary'>Create Game</button></div></form></div><hr/><div class='container' id='listGames'></div><hr/><div class='container' id='attackStrategy'></div><hr/><div class='container' id='strategyApplied'></div><div id='goToBattlefield'></div>";
    document.getElementById("setupScreen").innerHTML = html;

}

window.addEventListener("load", () => {

    let href = window.location.href;
    let pos1 = href.lastIndexOf("/");
    let route = href.substr(pos1);
    
    /*if(route === "/" ){
        register();
    }*/

    if(route === "/setupScreen" ){
        
        login();

        if(token && token.length > 0){

            setupScreen();
            listGames();
            listArmies();

        }
        else{
            location.href = "/";
        }
        
    }

    if(route === "/battlefield"){
        
        login();
        
        if(token && token.length > 0){

            listGames();

        }
        else{
            location.href = "/";
        }

    }
    
});
