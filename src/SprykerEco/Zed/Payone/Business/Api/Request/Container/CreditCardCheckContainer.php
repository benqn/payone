<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class CreditCardCheckContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_CREDITCARDCHECK;

    /**
     * @var int
     */
    protected $aid;

    /**
     * @var string
     */
    protected $cardpan;

    /**
     * @var string
     */
    protected $cardtype;

    /**
     * @var int
     */
    protected $cardexpiredate;

    /**
     * @var int
     */
    protected $cardcvc2;

    /**
     * @var int
     */
    protected $cardissuenumber;

    /**
     * @var string
     */
    protected $storecarddata;

    /**
     * @var string
     */
    protected $language;

    /**
     * @param int $cardcvc2
     *
     * @return void
     */
    public function setCardCvc2($cardcvc2): void
    {
        $this->cardcvc2 = $cardcvc2;
    }

    /**
     * @return int
     */
    public function getCardCvc2()
    {
        return $this->cardcvc2;
    }

    /**
     * @param int $cardexpiredate
     *
     * @return void
     */
    public function setCardExpireDate($cardexpiredate): void
    {
        $this->cardexpiredate = $cardexpiredate;
    }

    /**
     * @return int
     */
    public function getCardExpireDate()
    {
        return $this->cardexpiredate;
    }

    /**
     * @param int $cardissuenumber
     *
     * @return void
     */
    public function setCardIssueNumber($cardissuenumber): void
    {
        $this->cardissuenumber = $cardissuenumber;
    }

    /**
     * @return int
     */
    public function getCardIssueNumber()
    {
        return $this->cardissuenumber;
    }

    /**
     * @param string $cardpan
     *
     * @return void
     */
    public function setCardPan($cardpan): void
    {
        $this->cardpan = $cardpan;
    }

    /**
     * @return string
     */
    public function getCardPan()
    {
        return $this->cardpan;
    }

    /**
     * @param string $cardtype
     *
     * @return void
     */
    public function setCardType($cardtype): void
    {
        $this->cardtype = $cardtype;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardtype;
    }

    /**
     * @param string $storecarddata
     *
     * @return void
     */
    public function setStoreCardData($storecarddata): void
    {
        $this->storecarddata = $storecarddata;
    }

    /**
     * @return string|null
     */
    public function getStoreCardData(): ?string
    {
        return $this->storecarddata;
    }
}
