<?php

declare(strict_types=1);

namespace App\UI\Sequence;

use Exception;

use Laravel\Prompts\Progress;

use function Laravel\Prompts\progress;
use function Laravel\Prompts\spin;

readonly class Sequence
{
    public function __construct(private Items $sequence) {}

    /**
     * @throws Exception
     */
    public function run(): void
    {
        progress(
            label: $this->sequence->first()->title,
            steps: $this->sequence,
            callback: function (Item $item, Progress $progress) {
                $progress->label($item->title);
                spin(
                    callback: fn () => ($item->callback)(),
                    message: $item->description,
                );
            },
        );
    }
}
