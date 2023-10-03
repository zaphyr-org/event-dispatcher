<?php

declare(strict_types=1);

namespace Zaphyr\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author merloxx <merloxx@zaphyr.org>
 */
class ListenerProvider implements ListenerProviderInterface
{
    /**
     * @const int
     */
    public const PRIORITY_LOW = -100;

    /**
     * @const int
     */
    public const PRIORITY_NORMAL = 0;

    /**
     * @const int
     */
    public const PRIORITY_HIGH = 100;

    /**
     * @var array<string, array<int, array<int, callable>>>
     */
    protected array $listeners = [];

    /**
     * @param string   $eventName
     * @param callable $listener
     * @param int      $priority
     *
     * @return void
     */
    public function addListener(string $eventName, callable $listener, int $priority = self::PRIORITY_NORMAL): void
    {
        $this->listeners[$eventName][$priority][] = $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventName = get_class($event);
        $listeners = $this->listeners[$eventName] ?? [];

        krsort($listeners);

        foreach ($listeners as $listener) {
            foreach ($listener as $item) {
                yield $item;
            }
        }
    }
}
