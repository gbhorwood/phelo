<?php
namespace ghorwood\Phelo;

/**
 * An elo scoring system for php.
 *
 * @author gbh
 */
class Phelo
{
    /**
     * The k value used for calculating the elo score
     */
    private $k;

    /**
     * The Array of players, keyed by name, value of elo score
     */
    private $players = [];


    /**
     * Sets optional k value and constructs
     *
     * @param Int $k The optional k value used to calculate elo, default 30
     */
    public function __construct(int $k=30)
    {
        $this->k = $k;
    } // __construct


    /**
     * Creates a player by name with an optional elo. Default elo 1000.
     *
     * @param String $player The name of the player
     * @param Int $elo The initial elo of the player. Must be positive.
     * @return void
     * @throws \Exception Elo must be positive
     */
    public function __set(String $player, int $elo=1000)
    {
        if ($elo < 1) {
            throw new \Exception("Elo must be positive");
        }
        $this->players[$player] = $elo;
    } // __set


    /**
     * Returns The elo score of the player $player
     *
     * @param String $player The name of the player
     * @return Int The elo of player $player
     * @throws \Exception Invalid player
     */
    public function __get(String $player):int
    {
        if (!isset($this->players[$player])) {
            throw new \Exception("Invalid player $player");
        }
        return $this->players[$player];
    } // __get


    /**
     * Returns array of all players, keyed by name with value of elo.
     *
     * @return Array All players with their elos
     */
    public function getAll():array
    {
        return $this->players;
    } // getAll


    /**
     * Returns the chance of player $winner beating player $loser as
     * a percentage to decimal places.
     *
     * @param String $winner The name of the winning player
     * @param String $loser The name of the losing player
     * @return Float The chance that $winner has of beating $loser
     * @throws \Exception Invalid player
     */
    public function chance(string $winner, string $loser):float
    {
        return floatval(sprintf("%.2f", $this->chanceOutOfOne($winner, $loser) * 100));
    } // chance


    /**
     * Calculates the new elos for the player $winner and $loser
     * resulting from a match between them.
     *
     * This method is chainable.
     *
     * @param String $winner The name of the winning player
     * @param String $loser The name of the losing player
     * @return Phelo
     * @throws \Exception Invalid player
     */
    public function match(string $winner, string $loser):Phelo
    {
        if (!isset($this->players[$winner])) {
            throw new \Exception("Invalid player $winner");
        }

        if (!isset($this->players[$loser])) {
            throw new \Exception("Invalid player $loser");
        }
        
        $this->players[$winner] = round($this->players[$winner] + $this->k * (1 - $this->chanceOutOfOne($winner, $loser)));
        $this->players[$loser] = round($this->players[$loser] + $this->k*(0 - $this->chanceOutOfOne($loser, $winner)));
        return $this;
    } // match


    /**
     * Calculates the chance that player $winner has of beating player $loser
     * as a float between 0 and 1. Used by match() to calculate elo.
     *
     * @param String $winner The name of the winning player
     * @param String $loser The name of the losing player
     * @return Float The chance that $winner has of beating $loser
     * @throws \Exception Invalid player
     */
    private function chanceOutOfOne(string $winner, string $loser):float
    {
        if (!isset($this->players[$winner])) {
            throw new \Exception("Invalid player $winner");
        }

        if (!isset($this->players[$loser])) {
            throw new \Exception("Invalid player $loser");
        }
        
        return abs(1/(1 + pow(10, ($this->players[$loser]-$this->players[$winner])/400)));
    } // chanceOutOfOne
} // Phelo
