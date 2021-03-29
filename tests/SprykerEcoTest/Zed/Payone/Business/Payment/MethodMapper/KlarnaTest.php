<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\DataBuilder\PayoneKlarnaStartSessionRequestBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Klarna;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group KlarnaTest
 */
class KlarnaTest extends AbstractMethodMapperTest
{
    public const PAY_METHOD_TYPE = 'KIV';

    protected const STANDARD_PARAMETER_CLEARING_TYPE = 'fnc';

    protected const CLIENT_IP = '127.0.0.1';

    public const AUTHORIZATION_KLARNA_REQUIRED_PARAMS = [
        'financingtype' => self::PAY_METHOD_TYPE,
    ];

    protected const PREAUTHORIZATION_KLARNA_REQUIRED_PARAMS = [
        'financingtype' => self::PAY_METHOD_TYPE,
    ];

    protected const PREAUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
        'email' => self::DEFAULT_EMAIL,
    ];

    protected const AUTHORIZATION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'reference' => self::PAYMENT_REFERENCE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    public const PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS = [
        'lastname' => self::ADDRESS_LAST_NAME,
        'country' => self::COUNTRY_ISO2CODE,
        'language' => self::STANDARD_PARAMETER_LANGUAGE,
        'email' => self::DEFAULT_EMAIL,
    ];

    public const START_SESSION_COMMON_REQUIRED_PARAMS = [
        'aid' => self::STANDARD_PARAMETER_AID,
        'clearingtype' => self::STANDARD_PARAMETER_CLEARING_TYPE,
        'amount' => self::AMOUNT_FULL,
        'currency' => self::STANDARD_PARAMETER_CURRENCY,
    ];

    public const START_SESSION_PERSONAL_DATA_REQUIRED_PARAMS = [
        'lastname' => self::ADDRESS_LAST_NAME,
        'country' => self::COUNTRY_ISO2CODE,
        'language' => self::STANDARD_PARAMETER_LANGUAGE,
        'email' => self::DEFAULT_EMAIL,
    ];

    /**
     * @return void
     */
    public function testMapPaymentToPreauthorization(): void
    {
        // Arrange
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper($this->createKlarna());

        // Act
        $requestData = $paymentMethodMapper->mapPaymentToPreAuthorization($paymentEntity)->toArray();

        // Assert
        foreach (static::PREAUTHORIZATION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::PREAUTHORIZATION_KLARNA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToAuthorization(): void
    {
        // Arrange
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper($this->createKlarna());

        $orderTransfer = $this->getSalesOrderTransfer();

        // Act
        $requestData = $paymentMethodMapper->mapPaymentToAuthorization($paymentEntity, $orderTransfer)->toArray();

        // Assert
        foreach (static::AUTHORIZATION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::AUTHORIZATION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::AUTHORIZATION_KLARNA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToCapture(): void
    {
        // Arrange
        $paymentEntity = $this->getPaymentEntityMock();
        $paymentMethodMapper = $this->preparePaymentMethodMapper($this->createKlarna());

        // Act
        $requestData = $paymentMethodMapper->mapPaymentToCapture($paymentEntity)->toArray();

        // Assert
        foreach (static::CAPTURE_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return void
     */
    public function testMapPaymentToStartSession(): void
    {
        // Arrange
        $payoneKlarnaStartSessionRequest = $this->getPayoneKlarnaStartSessionRequest();
        $paymentMethodMapper = $this->preparePaymentMethodMapper($this->createKlarna());

        // Act
        $requestData = $paymentMethodMapper->mapPaymentToStartSession($payoneKlarnaStartSessionRequest)->toArray();

        // Assert
        foreach (static::START_SESSION_COMMON_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }

        foreach (static::START_SESSION_PERSONAL_DATA_REQUIRED_PARAMS as $key => $value) {
            $this->assertArrayHasKey($key, $requestData);
            $this->assertSame($value, $requestData[$key]);
        }
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Payment\MethodMapper\Klarna
     */
    protected function createKlarna(): Klarna
    {
        return new Klarna($this->getStoreConfigMock(), $this->getRequestStackMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail
     */
    protected function getPaymentPayoneDetailMock()
    {
        $paymentPayoneDetail = parent::getPaymentPayoneDetailMock();

        $paymentPayoneDetail->method('getPayMethod')->willReturn(static::PAY_METHOD_TYPE);
        $paymentPayoneDetail->method('getTokenList')->willReturn('token');

        return $paymentPayoneDetail;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStackMock(): RequestStack
    {
        $mock = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentRequest'])
            ->getMock();

        $mock->method('getCurrentRequest')->willReturn($this->getCurrentRequestMock());

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function getCurrentRequestMock(): Request
    {
        $mock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['getClientIp'])
            ->getMock();

        $mock->method('getClientIp')->willReturn(self::CLIENT_IP);

        return $mock;
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer
     */
    protected function getPayoneKlarnaStartSessionRequest(): PayoneKlarnaStartSessionRequestTransfer
    {
        $payoneKlarnaStartSessionRequestBuilder = new PayoneKlarnaStartSessionRequestBuilder([
            'quote' => $this->getQuote()
        ]);

        return $payoneKlarnaStartSessionRequestBuilder->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote(): QuoteTransfer
    {
        $quoteBuilder = new QuoteBuilder([
            'totals' => $this->getTotals(),
            'currency' => $this->createCurrency(),
            'billingAddress' => $this->getAddressMock(),
        ]);

        return $quoteBuilder->build();
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransferCurrencyTransfer
     */
    protected function createCurrency(): CurrencyTransfer
    {
        $currency = new CurrencyTransfer();
        $currency->setCode(static::STANDARD_PARAMETER_CURRENCY);

        return $currency;
    }
}
