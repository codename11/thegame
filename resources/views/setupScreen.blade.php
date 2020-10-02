@extends("app")

@section("content")

    <div class="container">

        <h3>setupScreen</h3>

        <form onsubmit="createGame(event)">

            <div class="form-group">
                <label for="gameName">Game Name:</label>
                <input type="text" class="form-control" placeholder="Enter name for the Game" name="gameName" id="gameName" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary">Create Game</button>
            </div>

        </form>
    </div>

    <hr/>

    <div class="container" id="listGames">

    </div>

    <hr/>

    <div class="container" id="attackStrategy">

    </div>

    <hr/>

    <div class="container" id="strategyApplied">

    </div>

    <div class="container" id="enoughArmies">

    </div>

    <div id="goToBattlefield"></div>

@endsection