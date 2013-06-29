<?php
namespace Moriony\Service\A1Sms;

class SendResponse extends AbstractResponse
{
    const DATA_ID = 'id';
    const DATA_STATUS = 'status';
    const DATA_MESSAGE = 'message';

    const SUCCESS = 'ok';
    const ERROR = 'error';

    protected function parseData($rawData)
    {
        if(preg_match('/^\d+$/', $rawData)) {
            $this->setData(self::DATA_STATUS, self::SUCCESS);
            $this->setData(self::DATA_ID, $rawData);
        } else {
            $this->setData(self::DATA_STATUS, self::ERROR);
            $this->setData(self::DATA_MESSAGE, $rawData);
        }
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
    public function isSuccess()
    {
        return self::SUCCESS == $this->getStatus();
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getData(self::DATA_MESSAGE);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getData(self::DATA_ID);
    }
}
