<?php


namespace calderawp\PusherAPI;


class Route
{

    const API_NAMESPACE = 'caldera/pusher/v1';
    const  ROUTE = 'plugin';

    public static function getRouteURL()
    {
        return rest_url( self::getRouteUri() );
    }

    public static function getRouteUri()
    {
        return self::API_NAMESPACE .'/' . self::ROUTE;
    }
    public function addRoutes()
    {
        register_rest_route( self::API_NAMESPACE, self::ROUTE, [
                'methods' =>\WP_REST_Server::ALLMETHODS,
                'callback' => [ $this, 'callback' ],
                'permission_callback' => [ $this, 'permissions' ],
                'args' => $this->getArgs()
        ]);
    }

    /**
     * @param \WP_REST_Request $request
     * @return bool
     */
    public function permissions( \WP_REST_Request $request )
    {

        /**
         * Should requests be allowed?
         *
         * @param bool $allow
         * @param \WP_REST_Request $request
         */
        return apply_filters( 'caldera_pusher_api_request_allow', false, $request );
    }

    public function callback( \WP_REST_Request $request )
    {
        $repo = $request[ 'repo' ];
        $basename = ! empty( $request[ 'basename' ] ) ? $request[ 'basename' ] : $repo;
        $branch = $request[ 'branch' ];
        $plugin = new Plugin( $repo, $basename, $request[ 'type' ] );
        //Delete plugin if it's installed
        $this->deleteIfInstalled($plugin);
        //If installing or updating, install and activate
        if( 'DELETE' !== $request->get_method() ){
            $plugin->installPlugin( $branch );
            if ( $request[ 'activate' ]) {
                $plugin->activate();
            }
        }

        $plugins = get_plugins();
        $return = isset( $plugins[$basename] ) ? $plugins[$basename] : [];
        $return[ 'active' ] = $plugin->isActive();
        return rest_ensure_response( $return );

    }

    /**
     * @param $plugin
     */
    protected function deleteIfInstalled(Plugin $plugin)
    {
        if ($plugin->isInstalled()) {
            $plugin->delete();
        }
    }

    /**
     * @return array
     */
    protected function getArgs()
    {
        /**
         * Filter endpoint args
         *
         * @param array $args
         */
        return apply_filters( 'caldera_pusher_api_request_args', [
            'repo' => [
                'type' => 'string',
                'required' => true,
                'description' => __('Repo name: vendor/repo')
            ],
            'type' => [
                'type' => 'string',
                'default' => 'gh',
                'description' => __('Type of repo: gh|bb|gl')
            ],
            'branch' => [
                'type' => 'string',
                'default' => 'master',
                'description' => __('Branch to install')
            ],
            'basename' => [
                'type' => 'string',
                'description' => __('Plugin basename')
            ],
            'activate' => [
                'type' => 'bool',
                'default' => false,
                'description' => __('Activate plugin?')
            ]
        ]);
    }

}