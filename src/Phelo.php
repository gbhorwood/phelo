<?php

declare(strict_types=1);

namespace ghorwood\Phelo;

/**
 * An elo scoring system for php.
 *
 * @author gbh
 */
class Phelo
{
    /**
     * The Array of players, keyed by name, value of elo score
     *
     * @var array<string, int>
     */
    private array $players = [];


    /**
     * Sets optional k value and constructs
     *
     * @param int $k The optional k value used to calculate elo, default 30
     */
    public function __construct(private readonly int $k = 30)
    {
    } // __construct


    /**
     * Creates a player by name with an optional elo. Default elo 1000.
     *
     * @param string $player The name of the player
     * @param int $elo The initial elo of the player. Must be positive.
     * @throws \InvalidArgumentException Elo must be positive
     */
    public function __set(string $player, int $elo = 1000): void
    {
        if ($elo < 1) {
            throw new \InvalidArgumentException("Elo must be positive");
        }
        $this->players[$player] = $elo;
    } // __set


    /**
     * Returns The elo score of the player $player
     *
     * @param string $player The name of the player
     * @return int The elo of player $player
     * @throws \InvalidArgumentException Invalid player
     */
    public function __get(string $player): int
    {
        $this->assertPlayer($player);
        return $this->players[$player];
    } // __get


    /**
     * Returns array of all players, keyed by name with value of elo.
     *
     * @return array<string, int> All players with their elos
     */
    public function getAll(): array
    {
        return $this->players;
    } // getAll


    /**
     * Returns the chance of player $winner beating player $loser as
     * a percentage to decimal places.
     *
     * @param string $winner The name of the winning player
     * @param string $loser The name of the losing player
     * @return float The chance that $winner has of beating $loser
     * @throws \InvalidArgumentException Invalid player
     */
    #[\NoDiscard]
    public function chance(string $winner, string $loser): float
    {
        return round($this->chanceOutOfOne($winner, $loser) * 100, 2);
    } // chance


    /**
     * Calculates the new elos for the player $winner and $loser
     * resulting from a match between them.
     *
     * This method is chainable.
     *
     * @param string $winner The name of the winning player
     * @param string $loser The name of the losing player
     * @throws \InvalidArgumentException Invalid player
     */
    public function match(string $winner, string $loser): static
    {
        $this->players[$winner] = (int) round($this->players[$winner] + $this->k * (1 - $this->chanceOutOfOne($winner, $loser)));
        $this->players[$loser] = (int) round($this->players[$loser] + $this->k * (0 - $this->chanceOutOfOne($loser, $winner)));
        return $this;
    } // match


    /**
     * Calculates the chance that player $winner has of beating player $loser
     * as a float between 0 and 1. Used by match() to calculate elo.
     *
     * @param string $winner The name of the winning player
     * @param string $loser The name of the losing player
     * @return float The chance that $winner has of beating $loser
     * @throws \InvalidArgumentException Invalid player
     */
    private function chanceOutOfOne(string $winner, string $loser): float
    {
        $this->assertPlayer($winner);
        $this->assertPlayer($loser);

        return 1 / (1 + 10 ** (($this->players[$loser] - $this->players[$winner]) / 400));
    } // chanceOutOfOne


    /**
     * Throws if player $player does not exist.
     *
     * @param string $player The name of the player
     * @throws \InvalidArgumentException Invalid player
     */
    private function assertPlayer(string $player): void
    {
        if (!isset($this->players[$player])) {
            throw new \InvalidArgumentException("Invalid player $player");
        }
    } // assertPlayer
} // Phelo
