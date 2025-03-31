<?php

declare(strict_types=1);

namespace App\UI\Sequence;
use Iterator;

class Items implements Iterator
{
    private int $position = 0;

    /**
     * @param  array<Item>  $steps
     */
    public function __construct(private readonly array $steps) {}

    public function current(): ?Item
    {
        return $this->steps[$this->position] ?? null;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->steps[$this->position]);
    }

    public function count(): int
    {
        return count($this->steps);
    }

    public function first(): Item
    {
        return $this->steps[0];
    }

}

