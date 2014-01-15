<?php
namespace Moriony\Service\A1Sms;

use Moriony\Service\A1Sms\Exception\InvalidLogin;
use Moriony\Service\A1Sms\Exception\InvalidMessage;
use Moriony\Service\A1Sms\Exception\InvalidPassword;
use Moriony\Service\A1Sms\Exception\InvalidPhoneNumber;
use Moriony\Service\A1Sms\Exception\InvalidSenderName;
use Moriony\Service\A1Sms\Exception\InvalidTransactionId;
use Moriony\Service\A1Sms\Response\SendResponse;
use Moriony\Service\A1Sms\Response\StatusResponse;

class Client
{
    const SEND_URL = "http://http.a1smsmarket.ru:8000/send";

    const OPT_OPERATION = 'operation';
    const OPT_LOGIN = 'login';
    const OPT_PASSWORD = 'password';
    const OPT_MSISDN = 'msisdn';
    const OPT_SHORTCODE = 'shortcode';
    const OPT_TEXT = 'text';
    const OPT_TRANSACTION_ID = 'id';

    const OPERATION_SEND = 'send';
    const OPERATION_STATUS = 'status';

    protected $login;
    protected $password;
    protected $sender;

    /**
     * @param string $login
     * @param string $password
     * @param string $sender
     * @throws Exception\InvalidLogin
     * @throws Exception\InvalidPassword
     */
    public function __construct($login, $password, $sender)
    {
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setSender($sender);
    }

    /**
     * @param string $login
     * @throws Exception\InvalidLogin
     * @return $this
     */
    public function setLogin($login)
    {
        if(!is_scalar($login)) {
            throw new InvalidLogin;
        }
        $this->login = $login;
        return $this;
    }

    /**
     * @param string $password
     * @throws Exception\InvalidPassword
     * @return $this
     */
    public function setPassword($password)
    {
        if(!is_scalar($password)) {
            throw new InvalidPassword;
        }
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $sender
     * @throws Exception\InvalidSenderName
     * @return $this
     */
    public function setSender($sender)
    {
        if(!is_scalar($sender) || !preg_match('/^[\d\w]{1,11}$/usi', $sender)) {
            throw new InvalidSenderName;
        }
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $operation
     * @param array $params
     * @return mixed
     */
    protected function call($operation, array $params)
    {
        $curl = curl_init();
        $params[self::OPT_OPERATION] = $operation;
        $params = http_build_query($params);
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::SEND_URL,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => $params,
        ));
        curl_exec($curl);
        $data = curl_multi_getcontent($curl);
        curl_close($curl);
        return $data;
    }

    /**
     * @param string $phone
     * @param string $message
     * @return SendResponse
     * @throws Exception\InvalidPhoneNumber
     * @throws Exception\InvalidSenderName
     * @throws Exception\InvalidMessage
     */
    public function send($phone, $message)
    {
        if(!is_scalar($phone) || !preg_match('/^[\d]{11,15}$/usi', $phone)) {
            throw new InvalidPhoneNumber;
        }
        if(!is_scalar($message)) {
            throw new InvalidMessage;
        }
        $params = array(
            self::OPT_LOGIN => $this->getLogin(),
            self::OPT_PASSWORD => $this->getPassword(),
            self::OPT_MSISDN => $phone,
            self::OPT_SHORTCODE => $this->getSender(),
            self::OPT_TEXT => $message,
        );
        $response = $this->call(self::OPERATION_SEND, $params);
        return new SendResponse($response);
    }

    /**
     * @param int $transactionId
     * @return StatusResponse
     * @throws Exception\InvalidTransactionId
     */
    public function status($transactionId)
    {
        if(!is_scalar($transactionId) || !preg_match('/^\d+$/usi', $transactionId)) {
            throw new InvalidTransactionId;
        }
        $params = array(
            self::OPT_LOGIN => $this->getLogin(),
            self::OPT_PASSWORD => $this->getPassword(),
            self::OPT_TRANSACTION_ID => $transactionId,
        );
        $response = $this->call(self::OPERATION_STATUS, $params);
        return new StatusResponse($response);
    }
}
