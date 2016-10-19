<?php


require_once __DIR__ . "/../src/api/SimpleApi.php";


class SimpleApiTest extends \PHPUnit_Framework_TestCase
{

    const END_POINT = "https://jsonplaceholder.typicode.com";

    /**
     * @var array
     */
    private $config = array(
        "time_out"         => 10,
        "connect_time_out" => 5,
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
            ->setEndPoint(self::END_POINT. "/posts/1")
            ->execute();

        $this->assertEquals($json->id, 1);

    }

    /**
     * post method test
     */
    public function testPostExecute()
    {
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->setEndPoint(self::END_POINT. "/posts")
            ->setMethod("POST")
            ->add("key", "value")
            ->execute();

        $this->assertEquals($json->id, 101);
    }

    /**
     * post method test
     */
    public function testEmptyBodyPostExecute()
    {
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->setEndPoint(self::END_POINT. "/posts")
            ->setMethod("POST")
            ->execute();

        $this->assertEquals($json->id, 101);
    }

    /**
     * put method test
     */
    public function testPutExecute()
    {
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->setEndPoint(self::END_POINT. "/posts/1")
            ->setMethod("PUT")
            ->execute();

        $this->assertEquals($json->id, 1);
    }

    /**
     * delete method test
     */
    public function testDeleteExecute()
    {
        $simpleApi = new \Api\SimpleApi($this->config);
        $json = $simpleApi
            ->setEndPoint(self::END_POINT. "/posts/1")
            ->setMethod("DELETE")
            ->execute();

        $this->assertTrue($json instanceof stdClass);
    }

}