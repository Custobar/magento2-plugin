<?php

namespace Custobar\CustoConnector\Model\Schedule;

interface LockControlInterface
{
    /**
     * Apply lock
     *
     * @return void
     */
    public function lock();

    /**
     * Remove lock
     *
     * @return void
     */
    public function unlock();

    /**
     * Check if lock is applied
     *
     * @return bool
     */
    public function isLocked();
}
