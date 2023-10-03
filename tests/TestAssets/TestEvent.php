<?php

declare(strict_types=1);

namespace Zaphyr\EventDispatcherTests\TestAssets;

use Zaphyr\EventDispatcher\AbstractStoppableEvent;

class TestEvent extends AbstractStoppableEvent
{
    protected array $numbers = [];

    public function add(int $number): void
    {
        $this->numbers[] = $number;
    }

    public function all(): array
    {
        return $this->numbers;
    }

    public function greet(): void
    {
        echo 'Hello World' . PHP_EOL;
    }
}
