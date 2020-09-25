<?php

namespace Custobar\CustoConnector\Block\Adminhtml\LogData\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Back implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    private $context;

    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * @inheritdoc
     */
    public function getButtonData()
    {
        return [
            'label' => \__('Back'),
            'on_click' => \sprintf(
                'location.href = \'%s\';',
                $this->context->getUrl('custobar/logs/index')
            ),
            'class' => 'back',
            'sort_order' => -9999,
        ];
    }
}
