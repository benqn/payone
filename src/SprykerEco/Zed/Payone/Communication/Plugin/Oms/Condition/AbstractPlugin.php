<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;

/**
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
abstract class AbstractPlugin extends BaseAbstractPlugin implements ConditionInterface
{
    public const NAME = 'AbstractPlugin';

    /**
     * @var array
     */
    private static $resultCache = [];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $order = $orderItem->getOrder();

        if (isset(self::$resultCache[$this->getName()][$order->getPrimaryKey()])) {
            return self::$resultCache[$this->getName()][$order->getPrimaryKey()];
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($order->toArray(), true);

        $isSuccess = $this->callFacade($orderTransfer);
        self::$resultCache[$order->getPrimaryKey()] = $isSuccess;

        return $isSuccess;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    abstract protected function callFacade(OrderTransfer $orderTransfer);

    /**
     * @return string
     */
    protected function getName()
    {
        return self::NAME;
    }
}
