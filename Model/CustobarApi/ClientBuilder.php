<?php

namespace Custobar\CustoConnector\Model\CustobarApi;

use Custobar\CustoConnector\Model\CustobarApi\ClientBuilder\VersionClientProviderResolverInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Exception\LocalizedException;

class ClientBuilder implements ClientBuilderInterface
{
    // TODO: Eventually can directly construct the LaminasClient instance here, whenever 2.4.6 becomes the oldest
    // supported version for this module. Right now this is just to cover for the 2.4.4 and 2.4.5 where
    // LaminasClient does not exist but which still are wanted to be supported.

    /**
     * @var ProductMetadataInterface
     */
    private $metadata;

    /**
     * @var VersionClientProviderResolverInterface[]
     */
    private $clientResolvers;

    /**
     * @param ProductMetadataInterface $metadata
     * @param VersionClientProviderResolverInterface[] $clientResolvers
     */
    public function __construct(
        ProductMetadataInterface $metadata,
        array $clientResolvers = []
    ) {
        $this->metadata = $metadata;
        $this->clientResolvers = $clientResolvers;
    }

    /**
     * @inheritDoc
     */
    public function buildClient(string $hostUrl, array $config)
    {
        $systemVersion = $this->metadata->getVersion();
        foreach ($this->clientResolvers as $index => $clientResolver) {
            if ($clientResolver === null) {
                continue;
            }

            if (!($clientResolver instanceof VersionClientProviderResolverInterface)) {
                throw new LocalizedException(__(
                    '\'%1\' must implement interface VersionClientProviderResolverInterface',
                    $index
                ));
            }

            if (!$clientResolver->doesVersionApply($systemVersion)) {
                continue;
            }

            $clientProvider = $clientResolver->getProvider();

            return $clientProvider->getClient($hostUrl, $config);
        }

        throw new LocalizedException(__('Unable to create HTTP client on version \'%1\'', $systemVersion));
    }
}
