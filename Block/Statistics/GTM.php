<?php

namespace Custobar\CustoConnector\Block\Statistics;

class GTM extends \Magento\Framework\View\Element\Template
{
    /**
     * @return mixed[]
     */
    public function getFieldMapping()
    {
        return $this->getData('gtm_field_mapping');
    }
}
