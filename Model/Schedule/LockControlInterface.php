<?php

namespace Custobar\CustoConnector\Model\Schedule;

interface LockControlInterface
{
    /**
     * @return void
     */
    public function lock();

    /**
     * @return void
     */
    public function unlock();

    /**
     * @return bool
     */
    public function isLocked();
}
