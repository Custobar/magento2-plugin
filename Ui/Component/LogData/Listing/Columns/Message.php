<?php

namespace Custobar\CustoConnector\Ui\Component\LogData\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

class Message extends Column
{
    public const LENGTH_LIMIT = 100;

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        $items = $dataSource['data']['items'] ?? [];
        foreach ($items as $index => $item) {
            $value = $item[$fieldName] ?? '';
            if (\strlen($value) > self::LENGTH_LIMIT) {
                $value = \substr($value, 0, self::LENGTH_LIMIT);
                $value .= '...';
            }

            $item[$fieldName] = $value;
            $items[$index] = $item;
        }
        $dataSource['data']['items'] = $items;

        return $dataSource;
    }
}
