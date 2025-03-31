<?php

declare(strict_types=1);

namespace App\Steps\Laravel;

use App\Steps\Step;
use App\UI\Sequence\Item;
use App\UI\Sequence\Items;
use App\UI\Sequence\Sequence;
use Exception;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class InstallBreeze extends Step
{
    /**
     * @throws Exception
     */
    public function __invoke(): void
    {
        $stack = select("Which Breeze stack would you like to install?", [
            "blade" => "Blade",
            "livewire" => "Livewire",
            "livewire-functional" => "Livewire Functional",
            "react" => "React",
            "vue" => "Vue",
            "api" => "Api",
        ]);
        $options = "";
        if (in_array($stack, ["react", "vue"])) {
            $options .= confirm("Would you like to use Inertia SSR support?", false) ? " --ssr" : "";
            $options .= confirm("Would you like to use TypeScript with Inertia?", false) ? " --typescript" : "";
        }
        $options .= confirm("Would you like to use ESLint and Prettier to lint your code?", false) ? " --eslint" : "";
        $options .= confirm("Would you like dark mode support?", false) ? " --dark" : "";
        $options .= confirm("Would you like to use the Pest testing framework?", false) ? " --pest" : "";

        $sequence = new Sequence(
            new Items([
                new Item(
                    "Composer require Breeze",
                    "composer require laravel/breeze --dev",
                    fn() => $this->composer->requireDev("laravel/breeze")
                ),
                new Item(
                    "Installing Breeze with configured options",
                    "php artisan breeze:install{$options} -- {$stack}",
                    fn() => $this->artisan->runCustom(
                        "breeze:install{$options} -- {$stack}"
                    )
                ),
                new Item(
                    "Run migrations",
                    "php artisan migrate",
                    fn() => $this->artisan->migrate()
                ),
                new Item(
                    "Commit changes",
                    'git add . && git commit -m "Install Laravel Breeze"',
                    fn() => $this->git->addAndCommit("Install Laravel Breeze")
                ),
            ])
        );

        $sequence->run();
    }

    public function name(): string
    {
        return "Laravel Breeze";
    }
}
