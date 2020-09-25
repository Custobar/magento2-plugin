<?php

namespace Custobar\CustoConnector\Model\Config\Source;

class TrackingMode implements \Magento\Framework\Data\OptionSourceInterface
{
    const MODE_NONE = 0;
    const MODE_CUSTOM_SCRIPT = 1;
    const MODE_GTM = 2;

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $optionArray = [];
        $options = $this->toArray();
        foreach ($options as $value => $label) {
            $optionArray[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $optionArray;
    }

    /**
     * @return mixed[]
     */
    public function toArray()
    {
        return [
            self::MODE_NONE => \__('None'),
            self::MODE_CUSTOM_SCRIPT => \__('Custom Script'),
            self::MODE_GTM => \__('Google Tag Manager'),
        ];
    }
}
