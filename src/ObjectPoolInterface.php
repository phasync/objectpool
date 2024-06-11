<?php

namespace phasync\Util;

interface ObjectPoolInterface
{
    /**
     * Invoke this function when it is certain that the object will no longer
     * be references or used. If it is impossible to determine, do NOT return
     * the object and let the garbage collector handle it.
     */
    public function returnToPool(): void;
}
