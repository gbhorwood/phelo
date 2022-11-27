# Phelo
Phelo is a simple library for managing the [elo rating system](https://en.wikipedia.org/wiki/Elo_rating_system).

The elo system is a rating method used in chess and other comptetitive games to calculate an estimate of the strength of the player, based on his or her performance versus other players.

## Installation
The preferred installation method is via composer

```shell
composer require ghorwood/phelo
```

## Usage
Phelo works by creating an object to which you can add an arbitrary number of players and their associated initial elo scores. You can then:

* Calculate the percentage chance of a given player defeating another player
* Match two players against each other, setting a winner, and harvest the resulting elos 

### Instantiation
Presuming you have used an autoloader, you can use and instantiate phelo as such:

```php
use ghorwood\Phelo\Phelo;

$phelo = new Phelo();
```

### Adding players
Players can be set directly into the Phelo object as such

```php
$phelo = new Phelo();

// Create player 'jasminder' with elo of 1000
$phelo->jasminder = 1000;

// You can also use variables. This is the method you should use for multi-word names.
$player1 = 'jasminder';
$phelo->$player1 = 1000;

$player2 = 'Barry Clarke';
$phelo->$player2 = 1000;

$player3 = "白百柏";
$phelo->$player3 = 1000;
```

### Getting a players elo
An individual player's elo can be retreived by referencing their name

```php
$phelo = new Phelo();

$phelo->jasminder = 1000;

print $phelo->jasminder; // 1000
```

All players can be retreived using getAll() which returns an array keyed by the player name.

```php
$phelo = new Phelo();

$phelo->jasminder = 1000;
$phelo->ahmed = 1200;

$all = $phelo->getAll();

print_r($all); // ['jasminder' => 1000, 'ahmed' => 1200]
```

### Calculating chance of win
If two players with different elo ratings are matched against each other, it is assumed the player with the higher elo score has a greater probability of winning. Phelo can calculate the percent chance of a given player defeating another player with the chance() method.

The chance() method takes two player names as arguments. The first argument is the player for whom the chance of winning will be calculated

```php
$phelo = new Phelo();

$phelo->Jerry = 1000;
$phelo->Sigrid = 910;

// Calculate the percent chance of Jerry winning by passing as first argument
$chanceJerryWins = $phelo->chance("Jerry", "Sigrid");
// Repeat, but for Sigrid
$chanceSigridWins = $phelo->chance("Sigrid", "Jerry");

// Chance of winning is a percent to two decimal places.
print $chanceJerryWins; // 62.67
print $chanceSigridWins; // 37.33

// Percentage chances add up to 100.00
print $chanceSigridWins + $chanceJerryWins; // 100.00
```

### Running matches to calculate elo changes
Elo scores change for players after they win or lose matches. Phelo provides the method match() to simulate a contest between two players. The leftmost of the two players is the winner. After a call to match() the new elo scores for the contestants is updated in the object and can be retreived.

```php
$phelo = new \ghorwood\Phelo\Phelo();

$phelo->Tyrone = 1200;
$phelo->Katarina = 800;

// Katarina defeats Tyrone
$phelo->match('Katarina', 'Tyrone');

// Get the updated elo scores
print $phelo->Katarina; // 827
print $phelo->Tyrone; // 1173
```

Calls to match() can be chained to simulate several consecutive contests.

```php
$phelo = new \ghorwood\Phelo\Phelo();

$phelo->Tyrone = 1200;
$phelo->Katarina = 800;

// Katarina defeats Tyrone in four consecutive matches
$phelo->match("Katarina", "Tyrone")
    ->match("Katarina", "Tyrone")
    ->match("Katarina", "Tyrone")
    ->match("Katarina", "Tyrone");

// Get the updated elo scores
print $phelo->Katarina; // 902
print $phelo->Tyrone; // 1099
```

### Errors
Phelo will throw an Exception in the following cases:

* Attempt to get a player that does not exist
* Attempt a match() or chance() on a player that does not exist
* Attempt to create a player with an elo less than 1

The use of try/catch blocks for Exception is encouraged.

### K factor
The K factor affects the sensitivity of changes: a higher K factor increases the changes in elo scores created by matches.

Choosig a K factor that is right for your requirements can be difficult. A good starting place to read on K factor is the ['Most accurate K-factor'](https://en.wikipedia.org/wiki/Elo_rating_system#Most_accurate_K-factor) sectionof the [wikipedia entry on elo](https://en.wikipedia.org/wiki/Elo_rating_system).

Phelo's default K factor is 30. You can change this to your custom value at instation by passing a new value to the contructor:

```php
// K factor FIDE uses for players under 18.
$phelo = new \ghorwood\Phelo\Phelo(40);
```

Gradation of K factor for different elo ratings is not supported in Phelo as this time.

## Getting support or contributing
Any prs should conform to PSR-2 formatting, pass phpstan static analysis at level 7 and be accompanied by tests.

The precommit.sh script is provided to do the formatting, analysis and tests.

Support requests, questions or bug reports should also be accompanied by a twitter dm to @gbhorwood, because i'm terrible at email.

