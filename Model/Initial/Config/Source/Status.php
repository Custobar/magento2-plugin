<?php

namespace Custobar\CustoConnector\Model\Initial\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    const STATUS_IDLE = 0;
    const STATUS_PROCESSED = 1;
    const STATUS_RUNNING = 2;

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $optionArray = [];
        $options = $this->getOptions();
        foreach ($options as $value => $label) {
            $optionArray[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $options;
    }

    /**
     * @return \Magento\Framework\Phrase[]
     */
    public function getOptions()
    {
        return [
            self::STATUS_IDLE => \__('Not running'),
            self::STATUS_PROCESSED => \__('Processed'),
            self::STATUS_RUNNING => \__('Export running'),
        ];
    }

    /**
     * @param int $status
     * @return \Magento\Framework\Phrase
     */
    public function getOptionLabel(int $status)
    {
        return $this->getOptions()[$status] ?? \__('Unknown');
    }
}
