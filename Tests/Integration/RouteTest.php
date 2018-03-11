<?php


namespace calderawp\PusherAPI\Tests\Integration;


use calderawp\PusherAPI\Route;

class RouteTest extends RestTestCase
{


    public function setUp()
    {
        $plugin = 'caldera-forms/caldera-core.php';
        if( is_plugin_active( $plugin ) ){
            delete_plugins( array($plugin) );
        }

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testInstall()
    {
        $basename = 'caldera-forms/caldera-core.php';
        $request  = new \WP_REST_Request( 'GET', Route::getRouteUri(),
            [
                'repo' => 'calderawp/caldera-forms',
                'basename' => $basename,
                'branch' => 'develop',
            ]

        );
        $response = rest_get_server()->dispatch( $request );
        $data     = $response->get_data();
        $plugins = get_plugins();
        $this->assertTrue( isset( $plugins[ $basename] ) );

    }

    public function testInstallAndActivate()
    {
        $basename = 'caldera-forms/caldera-core.php';
        $request  = new \WP_REST_Request( 'GET', Route::getRouteUri(),
            [
                'repo' => 'calderawp/caldera-forms',
                'basename' => $basename,
                'branch' => 'develop',
                'activate' => 'true'
            ]

        );
        $response = rest_get_server()->dispatch( $request );
        $plugins = get_plugins();
        $plugins = get_option( 'active_plugins', array() );

        return is_array( $plugins ) && in_array( $basename, $plugins );

    }



}