<?php

namespace Custobar\CustoConnector\Model\LogData\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Monolog\Logger;

class Type implements OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $options[] = [
            'value' => Logger::INFO,
            'label' => \__('Info'),
        ];
        $options[] = [
            'value' => Logger::DEBUG,
            'label' => \__('Debug'),
        ];
        $options[] = [
            'value' => Logger::ERROR,
            'label' => \__('Error'),
        ];
        $options[] = [
            'value' => Logger::WARNING,
            'label' => \__('Warning'),
        ];
        $options[] = [
            'value' => Logger::ALERT,
            'label' => \__('Alert'),
        ];
        $options[] = [
            'value' => Logger::CRITICAL,
            'label' => \__('Critical'),
        ];

        return $options;
    }
}
