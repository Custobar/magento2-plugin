<?php

namespace Custobar\CustoConnector\Ui\Component\LogData\Listing\Columns;

use Custobar\CustoConnector\Api\Data\LogDataInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        $items = $dataSource['data']['items'] ?? [];
        foreach ($items as $index => $item) {
            $item[$fieldName]['edit'] = [
                'href' => $this->context->getUrl(
                    'custobar/logs/view',
                    [LogDataInterface::LOG_ID => $item[LogDataInterface::LOG_ID]]
                ),
                'label' => __('View'),
                'hidden' => false,
            ];
            $items[$index] = $item;
        }
        $dataSource['data']['items'] = $items;

        return $dataSource;
    }
}
