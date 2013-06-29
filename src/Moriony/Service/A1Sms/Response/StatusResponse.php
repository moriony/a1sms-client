<?php
namespace Moriony\Service\A1Sms\Response;

class StatusResponse extends AbstractResponse
{
    const DATA_STATUS = 'status';

    const ERROR = 'error';
    const DELIVERED = 'delivered';
    const EXPIRED = 'expired';
    const UNDELIVERABLE = 'undeliverable';
    const REJECTED = 'rejected';
    const BILLED = 'billed';
    const ACCEPTED = 'accepted';

    protected static $statuses = array(
        self::DELIVERED,
        self::EXPIRED,
        self::REJECTED,
        self::UNDELIVERABLE,
        self::BILLED,
        self::ACCEPTED,
    );

    /**
     * @param mixed $rawData
     */
    protected function parseData($rawData)
    {
        $this->setData(self::DATA_STATUS, $rawData);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::DATA_STATUS);
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return self::ERROR == $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isDelivered()
    {
        return self::DELIVERED == $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return self::EXPIRED == $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isUndeliverable()
    {
        return self::UNDELIVERABLE == $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isRejected()
    {
        return self::REJECTED == $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isBilled()
    {
        return self::BILLED == $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isAccepted()
    {
        return self::ACCEPTED == $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isUnexpected()
    {
        return !in_array(self::DATA_STATUS, self::$statuses);
    }
}
