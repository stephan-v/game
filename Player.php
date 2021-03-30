<?php

class Player
{
    /**
     * The amount of coins the player has.
     *
     * @var int $coins
     */
    private $coins = 0;

    /**
     * Whether the player is in the penalty box or not.
     *
     * @var bool $inPenaltyBox
     */
    private $inPenaltyBox = false;

    /**
     * The current location of the player.
     *
     * @var int $location
     */
    private $location = 0;

    /**
     * The name of the player.
     *
     * @var $name
     */
    private $name;

    /**
     * Initialize a new player instance.
     *
     * @param string $name The name of the player.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Add a given amount of coins.
     *
     * @param int $coins The amount of coins to add.
     */
    public function addCoins(int $coins)
    {
        $this->coins += $coins;
    }

    /**
     * Get the amount of coins for the player.
     *
     * @return int The current amount of coins.
     */
    public function getCoins(): int
    {
        return $this->coins;
    }

    /**
     * Get the current location of the player.
     *
     * @return int The current location.
     */
    public function getLocation(): int
    {
        return $this->location;
    }

    /**
     * Get the name of the player.
     *
     * @return string The player name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Check if the player is in the penalty box.
     *
     * @return bool Returns true if the player is in the penalty box, otherwise false.
     */
    public function isInPenaltyBox(): bool
    {
        return $this->inPenaltyBox;
    }

    /**
     * Remove the player from the penalty box.
     */
    public function removeFromPenaltyBox()
    {
        $this->inPenaltyBox = false;
    }

    /**
     * Send the player to the penalty box.
     */
    public function sendToPenaltyBox()
    {
        $this->inPenaltyBox = true;
    }

    /**
     * Update the location with the given amount of steps.
     *
     * @param int $steps The amount of steps.
     */
    public function moveLocation(int $steps)
    {
        // Max 11 steps.
        $this->location = ($steps % 11) - 1;
    }
}