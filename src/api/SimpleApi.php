<?php

namespace Api;

require_once __DIR__ . "/SimpleApiInterface.php";
require_once __DIR__ . "/../exception/ApiException.php";

class SimpleApi implements SimpleApiInterface
{

    /**
     * @var string
     */
    protected $end_point = "";

    /**
     * @var string
     */
    protected $method = "GET";

    /**
     * @var int
     */
    protected $time_out = 10;

    /**
     * @var int
     */
    protected $connect_time_out = 5;

    /**
     * @var string
     */
    protected $content_type = "application/json";

    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $parameters = array();


    /**
     * SimpleApi constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        // time_out
        if (isset($config["time_out"])) {
            $this->setTimeOut($config["time_out"]);
        }

        // time_out
        if (isset($config["connect_time_out"])) {
            $this->setConnectTimeOut($config["connect_time_out"]);
        }

        // end_point
        if (isset($config["end_point"])) {
            $this->setEndPoint($config["end_point"]);
        }

        // method
        if (isset($config["method"])) {
            $this->setMethod($config["method"]);
        }

        // content_type
        if (isset($config["content_type"])) {
            $this->setContentType($config["content_type"]);
        }
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * @param  string $content_type
     * @return $this
     */
    public function setContentType($content_type = "application/json")
    {
        $this->content_type = $content_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($this->method);
    }

    /**
     * @param  string $method
     * @return $this
     */
    public function setMethod($method = "GET")
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->end_point;
    }

    /**
     * @param  string $end_point
     * @return $this
     */
    public function setEndPoint($end_point = "")
    {
        $this->end_point = $end_point;
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function add($key, $value)
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string JSON
     */
    public function createJson()
    {
        return json_encode($this->getParameters());
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param  array $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return $this
     */
    public function addHeader($key, $value)
    {
        $this->headers[] = $key .":". $value;
        return $this;
    }

    /**
     * clear
     */
    public function clearHeader()
    {
        $this->headers = array();
    }

    /**
     * @return int
     */
    public function getTimeOut()
    {
        return $this->time_out;
    }

    /**
     * @param  int   $time_out
     * @return $this
     */
    public function setTimeOut($time_out = 10)
    {
        $this->time_out = $time_out;
        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeOut()
    {
        return $this->connect_time_out;
    }

    /**
     * @param  int   $connect_time_out
     * @return $this
     */
    public function setConnectTimeOut($connect_time_out = 5)
    {
        $this->connect_time_out = $connect_time_out;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param  array $options
     * @return $this
     */
    public function setOptions($options = array())
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param  int    $key
     * @param  string $value
     * @return $this
     */
    public function addOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * clear options
     */
    public function clearOptions()
    {
        $this->options = array();
    }

    /**
     * @param  string $path
     * @return $this
     */
    public function setPem($path = "")
    {
        $this
            ->addOption(CURLOPT_SSL_VERIFYPEER, true)
            ->addOption(CURLOPT_CAINFO,         $path);
        return $this;
    }

    /**
     * execute
     */
    public function execute()
    {
        $curl = curl_init();

        // init
        $this
            ->addHeader("Content-Type",         trim($this->getContentType()))
            ->addOption(CURLOPT_TIMEOUT,        $this->getTimeOut())
            ->addOption(CURLOPT_CONNECTTIMEOUT, $this->getConnectTimeOut())
            ->addOption(CURLOPT_RETURNTRANSFER, true)
            ->addOption(CURLOPT_URL,            $this->getEndPoint())
            ->addOption(CURLOPT_CUSTOMREQUEST,  $this->getMethod())
            ->addOption(CURLOPT_HTTPHEADER,     $this->getHeaders());

        // query
        switch ($this->getMethod()) {
            case "POST":
            case "PUT" :
                if (count($this->getParameters()) > 0) {
                    $this
                        ->addOption(CURLOPT_POST,       true)
                        ->addOption(CURLOPT_POSTFIELDS, $this->createJson());
                }
                break;
        }

        // set option
        curl_setopt_array($curl, $this->getOptions());

        // execute
        try {
            $json = json_decode(curl_exec($curl));
        } catch (ApiException $e) {
            $json = json_decode(array("exception" => $e->getMessage()));
        }

        curl_close($curl);

        return $json;
    }
}