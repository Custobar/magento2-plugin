<?php

namespace Custobar\CustoConnector\Block\Adminhtml\System\Config\Fieldset;

use Custobar\CustoConnector\Block\Adminhtml\System\Config\Button\LockToggle;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;

class FieldMapping extends Fieldset
{
    /**
     * @inheritDoc
     */
    protected function _getChildrenElementsHtml(AbstractElement $element)
    {
        $html = parent::_getChildrenElementsHtml($element);
        /** @var LockToggle $block */
        $block = $this->getLayout()->createBlock(
            LockToggle::class,
            $this->getNameInLayout() . '_lock_toggle'
        );
        $block->setTemplate('Custobar_CustoConnector::system/config/button/lock-toggle.phtml');
        $html .= $block->toHtml();

        return $html;
    }
}
