<?php

namespace Custobar\CustoConnector\Model\Schedule;

class LockControl implements LockControlInterface
{
    /**
     * @var LockFlagFactory
     */
    private $flagFactory;

    /**
     * @param LockFlagFactory $flagFactory
     */
    public function __construct(
        LockFlagFactory $flagFactory
    ) {
        $this->flagFactory = $flagFactory;
    }

    /**
     * @inheritDoc
     */
    public function lock()
    {
        $flag = $this->getFlag();
        $flag->setFlagData(['locked' => true]);
        $flag->save();
    }

    /**
     * @inheritDoc
     */
    public function unlock()
    {
        $flag = $this->getFlag();
        $flag->delete();
    }

    /**
     * @inheritDoc
     */
    public function isLocked()
    {
        $flag = $this->getFlag();

        return $flag->getId() > 0;
    }

    /**
     * Get current flag instance
     *
     * @return LockFlag
     */
    private function getFlag()
    {
        return $this->flagFactory->create()->loadSelf();
    }
}
