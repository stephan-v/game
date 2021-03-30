<?php

include __DIR__.'/Game.php';
include __DIR__.'/Player.php';

$game = new Game();

$game->addPlayer(new Player('Chet'));
$game->addPlayer(new Player('Pat'));
$game->addPlayer(new Player('Sue'));

$game->start();

do {
    $game->rollDice();

    if (rand(0,9)) {
        $notAWinner = $game->wrongAnswer();
    } else {
        $notAWinner = $game->correctAnswer();
    }
} while ($notAWinner);