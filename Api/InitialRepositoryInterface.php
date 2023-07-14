<?php

namespace Custobar\CustoConnector\Api;

use Custobar\CustoConnector\Api\Data\InitialInterface;

interface InitialRepositoryInterface
{
    /**
     * Get initial entity by its entity id
     *
     * @param int $initialId
     *
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $initialId);

    /**
     * Get initial entity by its unique entity type
     *
     * @param string $entityType
     *
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByEntityType(string $entityType);

    /**
     * Save initial entity
     *
     * @param \Custobar\CustoConnector\Api\Data\InitialInterface $initial
     *
     * @return \Custobar\CustoConnector\Api\Data\InitialInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(InitialInterface $initial);

    /**
     * Delete initial entity
     *
     * @param \Custobar\CustoConnector\Api\Data\InitialInterface $initial
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(InitialInterface $initial);
}
