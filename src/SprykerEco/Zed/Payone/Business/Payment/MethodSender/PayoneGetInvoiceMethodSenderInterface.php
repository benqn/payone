<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;

interface PayoneGetInvoiceMethodSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer): PayoneGetInvoiceTransfer;
}
