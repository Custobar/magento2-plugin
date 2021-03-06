<?php

namespace Custobar\CustoConnector\Model\Export\ExportData\Processor;

use Custobar\CustoConnector\Api\LoggerInterface;
use Custobar\CustoConnector\Model\CustobarApi\ClientBuilderInterface;
use Custobar\CustoConnector\Model\CustobarApi\ClientUrlProviderInterface;
use Custobar\CustoConnector\Model\Export\ExportData\ProcessorInterface;
use Custobar\CustoConnector\Api\Data\ExportDataInterface;
use Magento\Framework\Serialize\Serializer\Json;

class ExecuteExport implements ProcessorInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * @var ClientUrlProviderInterface
     */
    private $urlProvider;

    /**
     * @var ClientBuilderInterface
     */
    private $clientBuilder;

    public function __construct(
        LoggerInterface $logger,
        Json $jsonSerializer,
        ClientUrlProviderInterface $urlProvider,
        ClientBuilderInterface $clientBuilder
    ) {
        $this->logger = $logger;
        $this->jsonSerializer = $jsonSerializer;
        $this->urlProvider = $urlProvider;
        $this->clientBuilder = $clientBuilder;
    }

    /**
     * @inheritDoc
     */
    public function execute(ExportDataInterface $exportData)
    {
        $entityType = $exportData->getEntityType();
        $mappingData = $exportData->getMappingData();
        $requestData = $exportData->getRequestDataJson();
        $attemptedIds = $exportData->getAttemptedScheduleIds();
        $failedIds = $exportData->getFailedScheduleIds();

        if (empty($requestData) || empty($mappingData)) {
            $this->logger->error(\__('Will not export %1, empty request data', $entityType));

            $failedIds = \array_merge($failedIds, $attemptedIds);
            $exportData->setFailedScheduleIds(\array_values(\array_unique($failedIds)));
            $exportData->setSuccessfulScheduleIds([]);

            return $exportData;
        }

        try {
            $targetField = $mappingData->getTargetField();
            $hostUrl = $this->urlProvider->getUploadUrl($targetField);
            $client = $this->clientBuilder->buildClient($hostUrl, ['timeout' => 5]);

            $client->setRawData($requestData);
            $response = $client->request();
            $responseBody = \trim($response->getBody());
            $exportData->setRequestDataJson($responseBody);
            $responseBody = $this->jsonSerializer->unserialize($responseBody);

            $errorMessage = $responseBody['error']['reason'] ?? '';
            $responseCode = $responseBody['response'] ?? null;

            if (\strtolower($responseCode) != 'ok' || !empty($errorMessage)) {
                $this->logger->error(\__(
                    'Export request failed with code %1: %2',
                    $response->getStatus(),
                    $errorMessage
                ), [
                    'targetUrl' => $hostUrl,
                    'request' => $this->jsonSerializer->unserialize($requestData),
                    'response' => $responseBody,
                ]);

                $failedIds = \array_merge($failedIds, $attemptedIds);
                $exportData->setFailedScheduleIds($failedIds);
                $exportData->setSuccessfulScheduleIds([]);

                return $exportData;
            }

            $successIds = $attemptedIds;
            $scheduleCount = \count($successIds);
            $this->logger->info("Export successful for {$scheduleCount} items of type {$entityType}", [
                'targetUrl' => $hostUrl,
                'request' => $this->jsonSerializer->unserialize($requestData),
                'response' => $responseBody,
            ]);

            $exportData->setSuccessfulScheduleIds($successIds);

            return $exportData;
        } catch (\Exception $e) {
            $this->logger->critical("Export request failed with message: {$e->getMessage()}", [
                'exceptionTrace' => $e->getTrace(),
            ]);

            $failedIds = \array_merge($failedIds, $attemptedIds);
            $exportData->setFailedScheduleIds($failedIds);
            $exportData->setSuccessfulScheduleIds([]);

            return $exportData;
        }
    }
}
