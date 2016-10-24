<?php


require_once __DIR__ . "/../src/api/SimpleApi.php";


class SimpleApiTest extends \PHPUnit_Framework_TestCase
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
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->execute();

        $this->assertArrayHasKey("get", $json);
        $this->assertEquals($json["get"], "OK");

    }

    /**
     * post method test
     */
    public function testPostExecute()
    {
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->setMethod("POST")
            ->add("post", "test")
            ->execute();

        $this->assertArrayHasKey("post", $json);
        $this->assertEquals($json["post"], "OK");
    }

    /**
     * post method test
     */
    public function testEmptyRowBodyPostExecute()
    {
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->setMethod("POST")
            ->execute();

        $this->assertArrayHasKey("post", $json);
        $this->assertEquals($json["post"], "OK");
    }

    /**
     * put method test
     */
    public function testPutExecute()
    {
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->setMethod("PUT")
            ->execute();

        $this->assertArrayHasKey("put", $json);
        $this->assertEquals($json["put"], "OK");
    }

    /**
     * delete method test
     */
    public function testDeleteExecute()
    {
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->setMethod("DELETE")
            ->execute();

        $this->assertArrayHasKey("delete", $json);
        $this->assertEquals($json["delete"], "OK");
    }

}