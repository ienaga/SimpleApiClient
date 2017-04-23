<?php

require_once __DIR__ . "/../src/api/Client.php";
require __DIR__ . "/../vendor/autoload.php";

class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    const END_POINT = "https://private-f8e3e3-simpleapitest.apiary-mock.com/test";

    /**
     * @var array
     */
    private $config = array(
        "time_out"         => 10,
        "connect_time_out" => 5,
        "end_point"        => self::END_POINT,
        "method"           => "GET",
        "content_type"     => "application/json"
    );

    /**
     * get method test
     */
    public function testGetExecute()
    {
        $simpleApi = new \SimpleApi\Client($this->config);
        $json = $simpleApi
            ->send();

        $this->assertArrayHasKey("get", $json);
        $this->assertEquals($json["get"], "OK");
    }

    /**
     * post method test
     */
    public function testPostExecute()
    {
        $simpleApi = new \SimpleApi\Client($this->config);
        $json = $simpleApi
            ->setMethod("POST")
            ->addBody("post", "test")
            ->send();

        $this->assertArrayHasKey("post", $json);
        $this->assertEquals($json["post"], "OK");
    }

    /**
     * post method test
     */
    public function testEmptyRowBodyPostExecute()
    {
        $simpleApi = new \SimpleApi\Client($this->config);
        $json = $simpleApi
            ->setMethod("POST")
            ->send();

        $this->assertArrayHasKey("post", $json);
        $this->assertEquals($json["post"], "OK");
    }

    /**
     * put method test
     */
    public function testPutExecute()
    {
        $simpleApi = new \SimpleApi\Client($this->config);
        $json = $simpleApi
            ->setMethod("PUT")
            ->send();

        $this->assertArrayHasKey("put", $json);
        $this->assertEquals($json["put"], "OK");
    }

    /**
     * delete method test
     */
    public function testDeleteExecute()
    {
        $simpleApi = new \SimpleApi\Client($this->config);
        $json = $simpleApi
            ->setMethod("DELETE")
            ->send();

        $this->assertArrayHasKey("delete", $json);
        $this->assertEquals($json["delete"], "OK");
    }

    /**
     * path test
     */
    public function testPath()
    {
        $simpleApi = new \SimpleApi\Client($this->config);

        $url = $simpleApi
            ->setPath("/a/b/c/")
            ->buildURL();

        $this->assertEquals($url, self::END_POINT ."/a/b/c");
    }

    /**
     * parameter test
     */
    public function testParameter()
    {
        $simpleApi = new \SimpleApi\Client($this->config);
        $url = $simpleApi
            ->addParameter("a", "1")
            ->addParameter("b", "2")
            ->addParameter("c", "3")
            ->buildURL();

        list($path, $param) = explode("?", $url);
        $this->assertEquals($path,  self::END_POINT);
        $this->assertEquals($param, "a=1&b=2&c=3");
    }

    /**
     * error test
     */
    public function testError()
    {
        $this->config["end_point"] = "https://404.error";
        $simpleApi = new \SimpleApi\Client($this->config);

        try {
            $simpleApi->send();
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), "Couldn't resolve host '404.error'");
        }
    }

}