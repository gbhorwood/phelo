<?php

declare(strict_types=1);

namespace Tests\Unit;

use ghorwood\Phelo\Phelo;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class EloTest extends TestCase
{
    private const array PLAYERS = [
        "Jasminder" => 1000,
        "Steve" => 1100,
        "Mahmoud" => 1200,
        "Joan" => 1300,
        "白百柏" => 1400,
        "Barry Wang" => 2000
    ];

    /**
     * Test setting and getting
     */
    #[Test]
    public function setGet(): void
    {
        $phelo = new Phelo();

        foreach (self::PLAYERS as $name => $elo) {
            $phelo->$name = $elo;
            $this->assertSame($elo, $phelo->$name);
        }
    }

    /**
     * Test getAll
     */
    #[Test]
    public function getAll(): void
    {
        $phelo = new Phelo();

        foreach (self::PLAYERS as $name => $elo) {
            $phelo->$name = $elo;
        }

        $this->assertSame(self::PLAYERS, $phelo->getAll());
    }

    /**
     * Test chance
     */
    #[Test]
    public function chance(): void
    {
        $phelo = new Phelo();
        $phelo->Khalid = 1000;
        $phelo->Jerry = 1000;
        $phelo->Sigrid = 910;

        $this->assertSame(50.0, $phelo->chance("Khalid", "Jerry"));

        $chanceJerry = $phelo->chance("Jerry", "Sigrid");
        $chanceSigrid = $phelo->chance("Sigrid", "Jerry");

        $this->assertSame(62.67, $chanceJerry);
        $this->assertSame(37.33, $chanceSigrid);
        $this->assertEqualsWithDelta(100.0, $chanceJerry + $chanceSigrid, 0.001);
    }

    /**
     * Test match, predicted elos with k 30, chaining
     */
    #[Test]
    public function match(): void
    {
        $phelo = new Phelo();

        $phelo->Tyrone = 1200;
        $phelo->Katarina = 800;

        $phelo->match("Katarina", "Tyrone")
            ->match("Katarina", "Tyrone")
            ->match("Katarina", "Tyrone")
            ->match("Katarina", "Tyrone");

        $this->assertSame(902, $phelo->Katarina);
        $this->assertSame(1099, $phelo->Tyrone);
        $this->assertSame(["Tyrone" => 1099, "Katarina" => 902], $phelo->getAll());
    }

    /**
     * Test setting non-positive elo fails
     */
    #[Test]
    public function setFail(): void
    {
        $phelo = new Phelo();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Elo must be positive");

        $phelo->fail = 0;
    }

    /**
     * Test getting non-existent player
     */
    #[Test]
    public function getFail(): void
    {
        $phelo = new Phelo();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid player Nonexistant");

        $phelo->Nonexistant;
    }

    /**
     * Test match with non-existent player
     */
    #[Test]
    public function matchFail(): void
    {
        $phelo = new Phelo();
        $phelo->Tyrone = 1200;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid player Nonexistant");

        $phelo->match("Tyrone", "Nonexistant");
    }
}
