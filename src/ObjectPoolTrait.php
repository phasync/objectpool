<?php

namespace phasync\Util;

trait ObjectPoolTrait
{
    private static array $pool        = [];
    private static int $instanceCount = 0;

    /**
     * Extract an instance from the object pool and return it.
     * Returns `null` if no instances are available.
     *
     * @returns ?static
     */
    protected static function popInstance(): ?static
    {
        if (0 === self::$instanceCount) {
            return null;
        }

        return self::$pool[--self::$instanceCount];
    }

    /**
     * Add this instance to the object pool. This function is intended
     * to be called by the {@see ObjectPoolTrait::returnToPool()} function
     * after the instance has been cleaned up for any references and stuff.
     */
    final protected function pushInstance(): void
    {
        self::$pool[self::$instanceCount++] = $this;
    }

    abstract public function returnToPool(): void;
}
