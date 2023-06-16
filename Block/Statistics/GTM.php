<?php

namespace Custobar\CustoConnector\Block\Statistics;

use Magento\Framework\View\Element\Template;

class GTM extends Template
{
    /**
     * Get field mapping from block data
     *
     * @return mixed[]
     */
    public function getFieldMapping()
    {
        return $this->getData('gtm_field_mapping');
    }
}
