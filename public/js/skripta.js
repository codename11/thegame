async function createGame(event){

    event.preventDefault();
    let forma = event.target;
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");

    let data = {
        gameName: forma.elements[0].value
    };

    let url = "/api/createGame";
    await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        console.log(data);
        listGames();

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

async function listGames(){

    let url = "/api/listGames";
    await fetch(url, {
        method: 'GET', // *GET, POST, PUT, DELETE, etc.
        headers: {
            'Content-Type': 'application/json',
        },
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        console.log(data);
        let games = data.games;
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
        

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

async function addArmy(event){

    event.preventDefault();
    let forma = event.target;
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    
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
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        console.log(data);
        forma.elements[0].value = gameId;
        forma.elements[1].value = null;
        forma.elements[2].value = null;

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
            'Content-Type': 'application/json',
        },
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        console.log(data);
        let armies = data.army;
        let len = armies.length;

        let html = "";
        if(len>0){

            let attackingArmies = "<h3>Select a army and add a strategy</h3><form onsubmit='attackStrategy(event)'><div class='form-group'><label for='armylist'>Select army:</label><select class='form-control' id='armylist' name='armylist'>";
            
            for(let i=0;i<len;i++){

                attackingArmies += "<option value="+armies[i].id+">"+armies[i].name+"</option>"

            }
            attackingArmies += "</select></div>";

            let strategies = "<div class='form-group'><label for='strategy'>Strategies:</label><select class='form-control' id='strategy' name='strategy'><option value='1'>Random</option><option value='2'>Weakest</option><option value='3'>Strongest</option></select></div>";

            html += attackingArmies + strategies + "<div class='form-group'><button type='submit' class='btn btn-outline-primary'>Attack Strategy</button></div></form>";
            document.getElementById("attackStrategy").innerHTML = html;

        }
        

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

async function attackStrategy(event){

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
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
     })
    .then((response) => {

        return response.json();

    })// parses JSON response into native JavaScript objects
    .then((data) => {

        console.log(data);
        document.getElementById("winner").innerHTML = "";
        let AttackingArmy = data.AttackingArmy.name;
        let ArmyToAttack = data.ArmyToAttack.name === AttackingArmy ? data.ArmyToAttack.name+"(copy)" : data.ArmyToAttack.name;
        document.getElementById("winner").innerHTML = "<div>"+AttackingArmy+" vs "+ArmyToAttack+"</div><h4>Winner is: "+data.Winner+"</h4>";

    })
    .catch((error) => {

          console.error('Error:', error);

    });

}

window.addEventListener("load", () => {

    listGames();
    listArmies();
});

