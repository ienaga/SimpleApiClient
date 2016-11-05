<?php

namespace SimpleApi;

require_once __DIR__ . "/ClientApiInterface.php";
require_once __DIR__ . "/../exception/ApiException.php";

class Client implements ClientApiInterface
{

    /**
     * @var string
     */
    protected $end_point = "";

    /**
     * @var string
     */
    protected $path = "";

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
     * @var string
     */
    protected $charset = "UTF-8";

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
     * @var null
     */
    protected $mh = null;

    /**
     * @var array
     */
    protected $curls = array();


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

        // charset
        if (isset($config["charset"])) {
            $this->setCharset($config["charset"]);
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
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param  string $charset
     * @return $this
     */
    public function setCharset($charset = "UTF-8")
    {
        $this->charset = $charset;
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
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param  string $path
     * @return $this
     */
    public function setPath($path = "")
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function add($key, $value = "")
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
     * @param  array $parameters
     * @return $this
     */
    public function setParameters($parameters = array())
    {
        $this->parameters = $parameters;
        return $this;
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
     * @return string
     */
    public function getURL()
    {
        $url = $this->getEndPoint();
        if ($this->getPath()) {
            $url .= $this->getPath();
        }
        return $url;
    }

    /**
     * @param  $curl
     * @return void
     */
    public function preSend($curl)
    {
        // init
        $this
            ->addHeader("Content-Type",         trim($this->getContentType()))
            ->addOption(CURLOPT_ENCODING,       $this->getCharset())
            ->addOption(CURLOPT_TIMEOUT,        $this->getTimeOut())
            ->addOption(CURLOPT_CONNECTTIMEOUT, $this->getConnectTimeOut())
            ->addOption(CURLOPT_RETURNTRANSFER, true)
            ->addOption(CURLOPT_URL,            $this->getURL())
            ->addOption(CURLOPT_CUSTOMREQUEST,  $this->getMethod())
            ->addOption(CURLOPT_HTTPHEADER,     $this->getHeaders());

        // query
        switch ($this->getMethod()) {
            case "POST":
            case "PUT" :
                if (count($this->getParameters()) > 0) {
                    $sendData = $this->createJson();
                    $this
                        ->addHeader("Content-length",   strlen($sendData))
                        ->addOption(CURLOPT_POST,       true)
                        ->addOption(CURLOPT_POSTFIELDS, $sendData);
                }
                break;
        }

        curl_setopt_array($curl, $this->getOptions());
    }

    /**
     * send
     * @return array
     */
    public function send()
    {
        $curl = curl_init();

        $this->preSend($curl);

        try {
            $json = json_decode(curl_exec($curl), true);
        } catch (ApiException $e) {
            $json = array("exception" => $e->getMessage());
        }

        curl_close($curl);

        return $json;
    }

    /**
     * add multi handle
     */
    public function addHandle()
    {
        if ($this->mh === null) {
            $this->mh = curl_multi_init();
        }

        $curl = curl_init();

        $this->preSend($curl);

        curl_multi_add_handle($this->mh, $curl);

        $this->curls[] = $curl;
    }

    /**
     * execute multi
     */
    public function multi()
    {
        $active = null;

        do {
            curl_multi_exec($this->mh, $active);
        } while ($active);

        // remove multi handle
        foreach ($this->curls as $curl) {
            curl_multi_remove_handle($this->mh, $curl);
            curl_close($curl);
        }

        curl_multi_close($this->mh);
    }
}