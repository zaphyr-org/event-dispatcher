<?php

declare(strict_types=1);

namespace Zaphyr\EventDispatcherTests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Zaphyr\EventDispatcher\EventDispatcher;
use Zaphyr\EventDispatcher\ListenerProvider;
use Zaphyr\EventDispatcherTests\TestAssets\TestEvent;
use Zaphyr\EventDispatcherTests\TestAssets\TestListener;

class EventDispatcherTest extends TestCase
{
    /* -------------------------------------------------
     * DISPATCH
     * -------------------------------------------------
     */

    public function testDispatchReturnsEventObject(): void
    {
        $event = new TestEvent();
        $eventDispatcher = new EventDispatcher(new ListenerProvider());

        self::assertSame($event, $eventDispatcher->dispatch($event));
    }

    public function testDispatchCallsListeners(): void
    {
        $listenerProvider = new ListenerProvider();
        $listenerProvider->addListener(TestEvent::class, function (TestEvent $event) {
            $event->add(1);
        });
        $listenerProvider->addListener(TestEvent::class, function (TestEvent $event) {
            $event->add(2);
        });
        $listenerProvider->addListener(TestEvent::class, function (TestEvent $event) {
            $event->add(3);
        });

        $eventDispatcher = new EventDispatcher($listenerProvider);

        self::assertEquals("123", (implode($eventDispatcher->dispatch(new TestEvent())->all())));
    }

    public function testDispatchStoppableEvent(): void
    {
        $listenerProvider = new ListenerProvider();
        $listenerProvider->addListener(TestEvent::class, function (TestEvent $event) {
            $event->add(1);
        });
        $listenerProvider->addListener(TestEvent::class, function (TestEvent $event) {
            $event->add(2);
            $event->stopPropagation();
        });
        $listenerProvider->addListener(TestEvent::class, function (TestEvent $event) {
            $event->add(3);
        });

        $eventDispatcher = new EventDispatcher($listenerProvider);

        self::assertEquals("12", (implode($eventDispatcher->dispatch(new TestEvent())->all())));
    }

    public function testDispatchAlreadyStoppedEventCallsNoListeners(): void
    {
        $eventDispatcher = new EventDispatcher(new ListenerProvider());

        $event = new TestEvent();
        $event->stopPropagation();

        self::assertEquals('', (implode($eventDispatcher->dispatch($event)->all())));
    }

    public function testDispatchWithInvokableListeners(): void
    {
        $listenerProvider = new ListenerProvider();
        $listenerProvider->addListener(TestEvent::class, new TestListener());
        $listenerProvider->addListener(TestEvent::class, new TestListener());

        (new EventDispatcher($listenerProvider))->dispatch(new TestEvent());

        $this->expectOutputString('Hello World' . PHP_EOL . 'Hello World' . PHP_EOL);
    }

    public function testDispatchCanThrowException(): void
    {
        $this->expectException(Exception::class);

        $listenerProvider = new ListenerProvider();
        $listenerProvider->addListener(TestEvent::class, function () {
            throw new Exception();
        });

        (new EventDispatcher($listenerProvider))->dispatch(new TestEvent());
    }
}
