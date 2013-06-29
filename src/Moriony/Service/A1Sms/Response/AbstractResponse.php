<?php
namespace Moriony\Service\A1Sms;

abstract class AbstractResponse
{
    /**
     * @var mixed|null
     */
    protected $rawData;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param string $data
     */
    public function __construct($data = null)
    {
        $this->rawData = $data;
        $this->parseData($data);
    }

    abstract protected function parseData($rawData);

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getData($key = null)
    {
        $result = null;
        if(is_null($key)) {
            $result = $this->data;
        } elseif(array_key_exists($key, $this->data)) {
            $result = $this->data[$key];
        }
        return $result;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setData($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->rawData;
    }
}
