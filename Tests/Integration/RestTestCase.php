<?php


namespace calderawp\PusherAPI\Tests\Integration;


abstract class RestTestCase extends IntegrationTestCase
{

    /**
     * Test REST Server
     *
     * @var \WP_REST_Server
     */
    protected $server;

    /**
     * Namespaced route name
     *
     * DONT CHANGE THIS IN SUBCLASS LET $this->setNamespace() handle it
     *
     * @var string
     */
    protected $namespaced_route = '';

    public function setUp()
    {
        parent::setUp();
        /** @var \WP_REST_Server $wp_rest_server */
        global $wp_rest_server;
        $this->server = $wp_rest_server = new \WP_REST_Server;
        do_action('rest_api_init');

    }

    public function tearDown()
    {
        parent::tearDown();
    }

}