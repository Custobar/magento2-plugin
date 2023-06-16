<?php

namespace Custobar\CustoConnector\Model\Initial\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Phrase;

class Status implements OptionSourceInterface
{
    public const STATUS_IDLE = 0;
    public const STATUS_PROCESSED = 1;
    public const STATUS_RUNNING = 2;

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

        return $optionArray;
    }

    /**
     * Get options as associative array
     *
     * @return Phrase[]
     */
    public function getOptions()
    {
        return [
            self::STATUS_IDLE => __('Not running'),
            self::STATUS_PROCESSED => __('Processed'),
            self::STATUS_RUNNING => __('Export running'),
        ];
    }

    /**
     * Get label by initial status id
     *
     * @param int $status
     *
     * @return Phrase
     */
    public function getOptionLabel(int $status)
    {
        return $this->getOptions()[$status] ?? __('Unknown');
    }
}
