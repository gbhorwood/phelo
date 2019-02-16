<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class EloTest extends TestCase
{
    /**
     * Test setting and getting
     *
     * @return void
     */
    public function testSetGet()
    {
        $players = [
            "Jasminder" => 1000,
            "Steve" => 1100,
            "Mahmoud" => 1200,
            "Joan" => 1300,
            "白百柏" => 1400
        ];
    
        $phelo = new \ghorwood\Phelo\Phelo();

        foreach ($players as $name => $elo) {
            $phelo->$name = $elo;
            $this->assertEquals($phelo->$name, $elo);
        }
    }

    /**
     * Test getAll
     *
     * @return void
     */
    public function testGetAll()
    {
        $players = [
            "Jasminder" => 1000,
            "Steve" => 1100,
            "Mahmoud" => 1200,
            "Joan" => 1300,
            "白百柏" => 1400
        ];
    
        $phelo = new \ghorwood\Phelo\Phelo();

        foreach ($players as $name => $elo) {
            $phelo->$name = $elo;
        }

        $all = $phelo->getAll();

        foreach ($all as $name => $elo) {
            $this->assertEquals($phelo->$name, $elo);
        }
    }

    /**
     * Test chance
     *
     * @return void
     */
    public function testChance()
    {
        $players = [
            "Khalid" => 1000,
            "Jerry" => 1000,
            "Sigrid" => 910
        ];

        $phelo = new \ghorwood\Phelo\Phelo();

        foreach ($players as $player => $elo) {
            $phelo->$player = $elo;
        }

        $this->assertEquals($phelo->chance("Khalid", "Jerry"), 50);

        $chanceJerry = $phelo->chance("Jerry", "Sigrid");
        $chanceSigrid = $phelo->chance("Sigrid", "Jerry");

        $this->assertEquals($chanceJerry, 62.67);
        $this->assertEquals($chanceSigrid, 37.33);
        $this->assertEquals($chanceJerry+$chanceSigrid, 100.00);
    }

    /**
     * Test match, predicted elos with k 30, chaining
     *
     * @return void
     */
    public function testMatch()
    {
        $phelo = new \ghorwood\Phelo\Phelo();

        $phelo->Tyrone = 1200;
        $phelo->Katarina = 800;

        $phelo->match("Katarina", "Tyrone")
            ->match("Katarina", "Tyrone")
            ->match("Katarina", "Tyrone")
            ->match("Katarina", "Tyrone");

        $this->assertEquals($phelo->Katarina, 902);
        $this->assertEquals($phelo->Tyrone, 1099);
    }

    /**
     * Test setting non-positive elo fails
     *
     * @return void
     */
    public function testSetFail()
    {
        $phelo = new \ghorwood\Phelo\Phelo();

        try {
            $phelo->fail = 0;
        } catch (\Exception $e) {
            $this->assertEquals("Elo must be positive", $e->getMessage());
        }
    }

    /**
     * Test getting non-existant player
     *
     * @return void
     */
    public function testGetFail()
    {
        $phelo = new \ghorwood\Phelo\Phelo();

        try {
            $phelo->Nonexistant;
        } catch (\Exception $e) {
            $this->assertEquals("Invalid player Nonexistant", $e->getMessage());
        }
    }
}
