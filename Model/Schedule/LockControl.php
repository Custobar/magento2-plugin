<?php

namespace Custobar\CustoConnector\Model\Schedule;

use Magento\Framework\Flag\FlagResource;

class LockControl implements LockControlInterface
{
    /**
     * @var LockFlagFactory
     */
    private $flagFactory;

    /**
     * @var FlagResource
     */
    private $flagResource;

    /**
     * @param LockFlagFactory $flagFactory
     * @param FlagResource $flagResource
     */
    public function __construct(
        LockFlagFactory $flagFactory,
        FlagResource $flagResource
    ) {
        $this->flagFactory = $flagFactory;
        $this->flagResource = $flagResource;
    }

    /**
     * @inheritDoc
     */
    public function lock()
    {
        $flag = $this->getFlag();
        $flag->setFlagData(['locked' => true]);
        $this->flagResource->save($flag);
    }

    /**
     * @inheritDoc
     */
    public function unlock()
    {
        $flag = $this->getFlag();
        $this->flagResource->delete($flag);
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
