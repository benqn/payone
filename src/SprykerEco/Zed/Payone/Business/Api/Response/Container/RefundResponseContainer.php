<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class RefundResponseContainer extends AbstractResponseContainer
{
    /**
     * @var int
     */
    protected $txid;

    /**
     * @var string
     */
    protected $protect_result_avs;

    /**
     * @param string $protect_result_avs
     *
     * @return void
     */
    public function setProtectResultAvs(string $protect_result_avs): void
    {
        $this->protect_result_avs = $protect_result_avs;
    }

    /**
     * @return string
     */
    public function getProtectResultAvs()
    {
        return $this->protect_result_avs;
    }

    /**
     * @param int $txid
     *
     * @return void
     */
    public function setTxid(int $txid): void
    {
        $this->txid = $txid;
    }

    /**
     * @return int
     */
    public function getTxid()
    {
        return $this->txid;
    }
}
