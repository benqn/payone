<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\GetInvoiceResponseContainer;
use SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class PayoneGetInvoiceMethodSender implements PayoneGetInvoiceMethodSenderInterface
{
    public const ERROR_ACCESS_DENIED_MESSAGE = 'Access denied';

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface[]
     */
    protected $registeredMethodMappers;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface
     */
    protected $standartParameterMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader
     */
    protected $paymentMapperManager;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Payment\DataMapper\StandartParameterMapperInterface $standartParameterMapper
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReader $paymentMapperReader
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterTransfer $standardParameter,
        StandartParameterMapperInterface $standartParameterMapper,
        PaymentMapperReader $paymentMapperReader
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->standartParameterMapper = $standartParameterMapper;
        $this->paymentMapperReader = $paymentMapperReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer): PayoneGetInvoiceTransfer
    {
        $responseContainer = new GetInvoiceResponseContainer();
        $paymentEntity = $this->findPaymentByInvoiceTitleAndCustomerId(
            $getInvoiceTransfer->getReference(),
            $getInvoiceTransfer->getCustomerId()
        );

        if ($paymentEntity) {
            /** @var \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Invoice $paymentMethodMapper */
            $paymentMethodMapper = $this->paymentMapperReader->getRegisteredPaymentMethodMapper(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
            $requestContainer = $paymentMethodMapper->mapGetInvoice($getInvoiceTransfer);
            $this->standartParameterMapper->setStandardParameter($requestContainer, $this->standardParameter);
            $rawResponse = $this->executionAdapter->sendRequest($requestContainer);
            $responseContainer->init($rawResponse);
        } else {
            $this->setAccessDeniedError($responseContainer);
        }

        $getInvoiceTransfer->setRawResponse($responseContainer->getRawResponse());
        $getInvoiceTransfer->setStatus($responseContainer->getStatus());
        $getInvoiceTransfer->setErrorCode($responseContainer->getErrorcode());
        $getInvoiceTransfer->setInternalErrorMessage($responseContainer->getErrormessage());

        return $getInvoiceTransfer;
    }

    /**
     * @param string $invoiceTitle
     * @param int $customerId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function findPaymentByInvoiceTitleAndCustomerId($invoiceTitle, $customerId)
    {
        return $this->queryContainer->createPaymentByInvoiceTitleAndCustomerIdQuery($invoiceTitle, $customerId)->findOne();
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer $container
     *
     * @return void
     */
    protected function setAccessDeniedError(AbstractResponseContainer $container)
    {
        $container->setStatus(PayoneApiConstants::RESPONSE_TYPE_ERROR);
        $container->setErrormessage(static::ERROR_ACCESS_DENIED_MESSAGE);
        $container->setCustomermessage(static::ERROR_ACCESS_DENIED_MESSAGE);
    }
}
