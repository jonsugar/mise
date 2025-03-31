<?php

declare(strict_types=1);

namespace App\UI\Sequence;

use Closure;

readonly class Item
{
    public function __construct(
        public string $title,
        public string $description,
        public Closure $callback) {}
}
