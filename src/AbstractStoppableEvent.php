<?php

declare(strict_types=1);

namespace Zaphyr\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author merloxx <merloxx@zaphyr.org>
 */
abstract class AbstractStoppableEvent implements StoppableEventInterface
{
    /**
     * @var bool
     */
    protected bool $propagationStopped = false;

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    /**
     * @return void
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }
}
