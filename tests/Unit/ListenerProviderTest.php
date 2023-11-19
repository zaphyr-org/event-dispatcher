<?php

declare(strict_types=1);

namespace Zaphyr\EventDispatcherTests\Unit;

use PHPUnit\Framework\TestCase;
use stdClass;
use Zaphyr\EventDispatcher\ListenerProvider;

class ListenerProviderTest extends TestCase
{
    /**
     * @var ListenerProvider
     */
    protected ListenerProvider $listenerProvider;

    protected function setUp(): void
    {
        $this->listenerProvider = new ListenerProvider();
    }

    public function tearDown(): void
    {
        unset($this->listenerProvider);
    }

    /* -------------------------------------------------
     * ADD AND GET LISTENERS
     * -------------------------------------------------
     */

    public function testAddListenerPriorities(): void
    {
        $this->listenerProvider->addListener(stdClass::class, fn() => 1, ListenerProvider::PRIORITY_LOW);
        $this->listenerProvider->addListener(stdClass::class, fn() => 2);
        $this->listenerProvider->addListener(stdClass::class, fn() => 3);
        $this->listenerProvider->addListener(stdClass::class, fn() => 4, ListenerProvider::PRIORITY_HIGH);

        $listeners = iterator_to_array($this->listenerProvider->getListenersForEvent(new stdClass()));

        self::assertSame(4, $listeners[0]());
        self::assertSame(2, $listeners[1]());
        self::assertSame(3, $listeners[2]());
        self::assertSame(1, $listeners[3]());
    }

    public function testGetListenersForEventReturnsArray(): void
    {
        $this->listenerProvider->addListener(stdClass::class, fn() => 1);
        $this->listenerProvider->addListener(stdClass::class, fn() => 2);
        $this->listenerProvider->addListener(stdClass::class, fn() => 3);

        self::assertCount(
            3,
            iterator_to_array($this->listenerProvider->getListenersForEvent(new stdClass()))
        );
    }

    public function testGetListenersForEventReturnsEmptyArray(): void
    {
        self::assertCount(
            0,
            iterator_to_array($this->listenerProvider->getListenersForEvent(new \stdClass()))
        );
    }
}
