<?php

include __DIR__.'/Categories.php';

function echoln($string) {
    echo $string."\n";
}

class Game
{
    /**
     * The index of the current player.
     *
     * @var int $currentPlayerIndex
     */
    private $currentPlayerIndex = 0;

    /**
     * The players which are part of the game.
     *
     * @var array $players
     */
    private $players = [];

    /**
     * The stack of pop questions.
     *
     * @var array $popQuestions
     */
    private $popQuestions = [];

    /**
     * The stack of rock questions.
     *
     * @var array $rockQuestions
     */
    private $rockQuestions = [];

    /**
     * The stack of science questions.
     *
     * @var array $scienceQuestions
     */
    private $scienceQuestions = [];

    /**
     * The stack of sports questions.
     *
     * @var array $sportsQuestions
     */
    private $sportsQuestions = [];

    /**
     * Start a new game and pre-fill all card stacks.
     */
    public function start()
    {
        if (!$this->isPlayable()) {
            Throw new RuntimeException("Game requires more than {$this->playerCount()} player(s)");
        }

        for ($i = 0; $i < 50; $i++) {
            $this->popQuestions[] = "Pop Question {$i}";
            $this->scienceQuestions[] = "Science Question {$i}";
            $this->sportsQuestions[] = "Sports Question {$i}";
            $this->rockQuestions[] = "Rock Question {$i}";
        }
    }

    /**
     * Check whether the game is playable or not.
     *
     * @return bool Returns true if the game is playable, otherwise false.
     */
    public function isPlayable(): bool
    {
        return ($this->playerCount() >= 2);
    }

    /**
     * Add a new player to the game.
     *
     * @param Player $player The new player.
     */
    public function addPlayer(Player $player)
    {
        $this->players[] = $player;

        echoln("{$player->getName()} was added");
        echoln("They are player number: {$this->playerCount()}");
    }

    /**
     * Get the total amount of players.
     *
     * @return int The total of players that are part of the game.
     */
    private function playerCount(): int
    {
        return count($this->players);
    }

    /**
     * Get the current player instance.
     *
     * @return Player The current player.
     */
    private function getCurrentPlayer(): Player
    {
        return $this->players[$this->currentPlayerIndex];
    }

    /**
     * Roll the dice.
     */
    public function rollDice()
    {
        $currentPlayer = $this->getCurrentPlayer();
        $currentPlayerName = $currentPlayer->getName();
        $diceRoll = rand(1,6);

        echoln("{$currentPlayerName} is the current player");
        echoln("They have rolled a {$diceRoll}");

        if ($currentPlayer->isInPenaltyBox()) {
            if ($this->diceRollIsUneven($diceRoll)) {
                echoln("{$currentPlayerName} is getting out of the penalty box");

                $currentPlayer->removeFromPenaltyBox();
                $this->movePlayer($currentPlayer, $diceRoll);
            } else {
                echoln("{$currentPlayerName} is not getting out of the penalty box");

                $this->nextPlayer();
            }
        } else {
            $this->movePlayer($currentPlayer, $diceRoll);
        }
    }

    /**
     * Move the player.
     *
     * @param Player $player The current player.
     * @param int $steps The amount of steps to move.
     */
    private function movePlayer(Player $player, int $steps)
    {
        $player->moveLocation($steps);

        echoln("{$player->getName()}'s new location is {$player->getLocation()}");
        echoln("The category is {$this->currentCategory()}");

        $this->askQuestion();
    }

    /**
     * Check whether the dice roll was uneven.
     *
     * @param int $diceRoll The dice roll to check against.
     * @return bool Returns true if the dice roll was uneven, otherwise false.
     */
    private function diceRollIsUneven(int $diceRoll): bool
    {
        return !($diceRoll % 2);
    }

    /**
     * Ask a question from the current category.
     */
    private function askQuestion()
    {
        switch ($this->currentCategory()) {
            case Categories::POP:
                echoln(array_shift($this->popQuestions));
                break;
            case Categories::SCIENCE:
                echoln(array_shift($this->scienceQuestions));
                break;
            case Categories::SPORTS:
                echoln(array_shift($this->sportsQuestions));
                break;
            case Categories::ROCK:
                echoln(array_shift($this->rockQuestions));
                break;
        }
    }

    /**
     * Get the current category.
     *
     * @return string The current category.
     */
    private function currentCategory(): string
    {
        $currentPlayerLocation = $this->getCurrentPlayer()->getLocation();

        switch ($currentPlayerLocation % 4) {
            case 0:
                return Categories::POP;
            case 1:
                return Categories::SCIENCE;
            case 2:
                return Categories::SPORTS;
            default:
                return Categories::ROCK;
        }
    }

    /**
     * Correct answer.
     *
     * @return bool
     */
    public function correctAnswer(): bool
    {
        $currentPlayer = $this->getCurrentPlayer();

        echoln("Answer was correct!!!!");

        $currentPlayer->addCoins(1);

        echoln("{$currentPlayer->getName()} now has {$currentPlayer->getCoins()} Gold Coins.");

        if ($this->didPlayerWin()) {
            return false;
        };

        $this->nextPlayer();

        return true;
    }

    /**
     * Wrong answer.
     *
     * @return bool
     */
    public function wrongAnswer(): bool
    {
        $currentPlayer = $this->getCurrentPlayer();

        echoln("Question was incorrectly answered");
        echoln("{$currentPlayer->getName()} was sent to the penalty box");

        $currentPlayer->sendToPenaltyBox();
        $this->nextPlayer();

        return true;
    }

    /**
     * Pass the turn on to the next player.
     */
    private function nextPlayer()
    {
        $this->currentPlayerIndex++;

        if ($this->currentPlayerIndex === count($this->players)) {
            $this->currentPlayerIndex = 0;
        }
    }

    /**
     * Check if the player won the game.
     *
     * @return bool Returns true if the player won, otherwise false.
     */
    private function didPlayerWin(): bool
    {
        return $this->getCurrentPlayer()->getCoins() === 6;
    }
}