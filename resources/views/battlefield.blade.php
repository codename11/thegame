@extends("app")

@section("content")

    <div class="container" id="listGames">

    </div>

    <div id="winner"></div>

    <audio id="battleSounds">
        <source src="/sounds/gun_battle_sound.wav" type="audio/wav">
        <source src="/sounds/gun_battle_sound.mp3" type="audio/mpeg">
      Your browser does not support the audio element.
    </audio>

    <div class="container" id="noArmies">

    </div>

@endsection