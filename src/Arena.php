<?php

namespace App;

use Exception;

class Arena 
{
    private array $monsters;
    private Hero $hero;

    private int $size = 10;

    public function __construct(Hero $hero, array $monsters)
    {
        $this->hero = $hero;
        $this->monsters = $monsters;
    }

    public function getDistance(Fighter $startFighter, Fighter $endFighter): float
    {
        $Xdistance = $endFighter->getX() - $startFighter->getX();
        $Ydistance = $endFighter->getY() - $startFighter->getY();
        return sqrt($Xdistance ** 2 + $Ydistance ** 2);
    }

    public function touchable(Fighter $attacker, Fighter $defenser): bool 
    {
        return $this->getDistance($attacker, $defenser) <= $attacker->getRange();
    }

    public function isValidPosition(int $x, int $y): bool
    {
        return $x >= 0 && $y >= 0 && $x < $this->getSize() && $y < $this->getSize();
    }

    public function isPositionTaken(int $x, int $y): bool
    {
        foreach ([$this->getHero(), ...$this->getMonsters()] as $fighter) {
            if ($fighter->getX() === $x && $fighter->getY() === $y) {
                return true;
            }
        }
        return false;
    }

    public function move(Fighter $fighter, string $direction): void
    {
        $offsets = [
            "N" => ['x' => 0, 'y' => -1],
            "S" => ['x' => 0, 'y' => +1],
            "W" => ['x' => -1, 'y' => 0],
            "E" => ['x' => +1, 'y' => 0],
        ];
        if (!isset($offsets[$direction])) {
            throw new Exception("Invalid direction: $direction");
        }
        $destinationX = $fighter->getX() + $offsets[$direction]['x'];
        $destinationY = $fighter->getY() + $offsets[$direction]['y'];
        if (!$this->isValidPosition($destinationX, $destinationY)) {
            throw new Exception("Invalid position: $destinationX, $destinationY");
        }
        if ($this->isPositionTaken($destinationX, $destinationY)) {
            throw new Exception("Position already occupied: $destinationX, $destinationY");
        }
        $fighter->setX($destinationX);
        $fighter->setY($destinationY);
    }

    /**
     * Get the value of monsters
     */ 
    public function getMonsters(): array
    {
        return $this->monsters;
    }

    /**
     * Set the value of monsters
     *
     */ 
    public function setMonsters($monsters): void
    {
        $this->monsters = $monsters;
    }

    /**
     * Get the value of hero
     */ 
    public function getHero(): Hero
    {
        return $this->hero;
    }

    /**
     * Set the value of hero
     */ 
    public function setHero($hero): void
    {
        $this->hero = $hero;
    }

    /**
     * Get the value of size
     */ 
    public function getSize(): int
    {
        return $this->size;
    }
}
