<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class CashOnDeliveryContainer extends AbstractPaymentMethodContainer
{
    /**
     * @var string
     */
    protected $shippingprovider;

    /**
     * @param string $shippingprovider
     *
     * @return void
     */
    public function setShippingProvider($shippingprovider)
    {
        $this->shippingprovider = $shippingprovider;
    }

    /**
     * @return string
     */
    public function getShippingProvider()
    {
        return $this->shippingprovider;
    }
}
