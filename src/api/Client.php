<?php

namespace SimpleApi;

require_once __DIR__ . "/ClientApiInterface.php";
require_once __DIR__ . "/../exception/SimpleApiException.php";

use SimpleApi\Exception\SimpleApiException;

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
     * @var int
     */
    protected $port = 0;

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
    protected $body = array();

    /**
     * @var array
     */
    protected $parameter = array();

    /**
     * @var null
     */
    protected $curl = null;

    /**
     * multi handle
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

        if (isset($config["port"])) {
            $this->setPort($config["port"]);
        }
    }

    /**
     * @return null
     */
    public function getConnection()
    {
        return $this->curl;
    }

    /**
     * @param $curl
     */
    public function setConnection($curl = null)
    {
        $this->curl = $curl;
    }

    /**
     * @return bool
     */
    public function hasConnection()
    {
        return ($this->curl !== null);
    }

    /**
     * @return null
     */
    public function getMultiHandle()
    {
        return $this->mh;
    }

    /**
     * @param $mh
     */
    public function setMultiHandle($mh = null)
    {
        $this->mh = $mh;
    }

    /**
     * @return bool
     */
    public function hasMultiHandle()
    {
        return ($this->mh !== null);
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
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param  int $port
     * @return $this
     */
    public function setPort($port = 0)
    {
        $this->port = (int) $port;
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
     * @return $this
     */
    public function clearMethod()
    {
        $this->method = "GET";
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
    public function addBody($key, $value = "")
    {
        $this->body[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param  array $body
     * @return $this
     */
    public function setBody($body = array())
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param  array $body
     * @return $this
     */
    public function mergeBody($body = array())
    {
        $this->body = array_merge($this->body, $body);
        return $this;
    }

    /**
     * @return $this
     */
    public function clearBody()
    {
        $this->body = array();
        return $this;
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param array $parameter
     */
    public function setParameter($parameter = array())
    {
        $this->parameter = $parameter;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return $this
     */
    public function addParameter($key, $value = "")
    {
        $this->parameter[$key] = (string) $value;
        return $this;
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
    public function setHeaders($headers = array())
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
     * @return $this
     */
    public function clearHeader()
    {
        $this->headers = array();
        return $this;
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
    public function setConnectTimeOut($connect_time_out = 3)
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
     * @return $this
     */
    public function clearOptions()
    {
        $this->options = array();
        return $this;
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
    public function buildURL()
    {
        $url = $this->getEndPoint();
        if (mb_substr($url, -1) === "/") {
            $url = substr($url, 0, -1); // delete end slash
        }

        // port
        $port = $this->getPort();
        if ($port) {
            $url .= ":". $port;
        }

        // path
        $path = $this->getPath();
        if ($path) {
            if (mb_substr($path, 0, 1) !== "/") {
                $path = "/". $path;
            }
            if (mb_substr($path, -1) === "/") {
                $path = substr($path, 0, -1); // delete end slash
            }
            $url .= $path;
        }

        $parameter = $this->getParameter();
        if (count($parameter)) {
            $parameters = array();
            foreach ($parameter as $key => $value) {
                $parameters[] = $key ."=". urlencode($value);
            }
            $url .= "?". implode("&", $parameters);
        }

        return $url;
    }

    /**
     * @return $this
     */
    public function initOption()
    {
        return $this
            ->addOption(CURLOPT_RETURNTRANSFER, true)
            ->addOption(CURLOPT_FORBID_REUSE,   true)
            ->addOption(CURLOPT_POSTFIELDS,     "")
            ->addOption(CURLOPT_SSLVERSION,     1)
            ->addHeader("Content-Type",         trim($this->getContentType()))
            ->addOption(CURLOPT_ENCODING,       $this->getCharset())
            ->addOption(CURLOPT_TIMEOUT,        $this->getTimeOut())
            ->addOption(CURLOPT_CONNECTTIMEOUT, $this->getConnectTimeOut())
            ->addOption(CURLOPT_URL,            $this->buildURL())
            ->addOption(CURLOPT_CUSTOMREQUEST,  $this->getMethod())
            ->addOption(CURLOPT_HTTPHEADER,     $this->getHeaders());
    }

    /**
     * @return string
     */
    public function createJson()
    {
        return json_encode($this->getBody());
    }

    /**
     * @return $this
     */
    public function buildBody()
    {
        if (count($this->getBody()) > 0) {

            $json = $this->createJson();
            $this
                ->addHeader("Content-length", strlen($json))
                ->addOption(CURLOPT_POST, true)
                ->addOption(CURLOPT_POSTFIELDS, $json);

        }
        return $this;
    }

    /**
     * @return void
     */
    public function preSend()
    {
        $this
            ->initOption()
            ->buildBody();

        curl_setopt_array($this->getConnection(), $this->getOptions());
    }

    /**
     * post send (reset)
     */
    public function postSend()
    {
        $this
            ->clearMethod()
            ->clearBody()
            ->clearHeader()
            ->clearOptions();
    }

    /**
     * @return mixed
     * @throws SimpleApiException
     */
    public function send()
    {
        if (!$this->hasConnection()) {
            $this->setConnection(curl_init());
        }

        $this->preSend();

        $ch = $this->getConnection();

        // execute
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new SimpleApiException(curl_error($ch));
        }

        $json = json_decode($response, true);
        $this->postSend();

        return $json;
    }

    /**
     * add multi handle
     */
    public function append()
    {
        if (!$this->hasMultiHandle()) {
            $this->setMultiHandle(curl_multi_init());
        }

        // init
        $this
            ->initOption()
            ->buildBody();

        $curl = curl_init();
        curl_setopt_array($curl, $this->getOptions());
        curl_multi_add_handle($this->getMultiHandle(), $curl);

        $this->curls[] = $curl;
    }

    /**
     * post multi
     */
    public function postMulti()
    {
        $this->setMultiHandle(null);
        $this->curls = array();
    }

    /**
     * execute multi
     */
    public function multi()
    {
        $active = null;

        do {
            curl_multi_exec($this->getMultiHandle(), $active);
        } while ($active);

        // remove multi handle
        foreach ($this->curls as $curl) {
            curl_multi_remove_handle($this->getMultiHandle(), $curl);
            curl_close($curl);
        }

        curl_multi_close($this->getMultiHandle());

        $this->postMulti();
    }

    /**
     * destruct
     */
    public function __destruct()
    {
        if ($this->hasConnection()) {
            curl_close($this->getConnection());
        }

        if ($this->hasMultiHandle()) {
            $mh = $this->getMultiHandle();

            foreach ($this->curls as $curl) {
                curl_multi_remove_handle($mh, $curl);
                curl_close($curl);
            }

            curl_multi_close($mh);
        }
    }
}