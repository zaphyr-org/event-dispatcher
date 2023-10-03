<?php

declare(strict_types=1);

namespace Zaphyr\EventDispatcherTests\TestAssets;

/**
 * @author merloxx <merloxx@zaphyr.org>
 */
class TestListener
{
    public function __invoke(TestEvent $event): void
    {
        $event->greet();
    }
}
