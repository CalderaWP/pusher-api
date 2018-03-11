<?php


namespace calderawp\PusherAPI;


use Pusher\Commands\InstallPlugin;
use Pusher\Commands\UpdatePlugin;
use Pusher\Pusher;

class Plugin
{

    protected $repo;
    protected $type;
    protected  $basename;
    public function __construct( $repo, $basename, $type = 'gh' )
    {
        $this->repo = $repo;
        $this->basename = $basename;
        $this->type = $type;
        include_once ABSPATH .'/wp-admin/includes/file.php';

    }

    public function installPlugin( $branch = 'master' )
    {
        $command = new InstallPlugin($this->pluginArgs($branch));

        return $this->runCommand( $command );

    }

    /**
     * @param string $branch
     * @return mixed
     */
    public function update( $branch = 'master' )
    {
        $command = new UpdatePlugin(array_merge(['file' => $this->basename],$this->pluginArgs($branch)));
        return $this->runCommand( $command );
    }

    /**
     * Is plugin installed?
     *
     * @return bool
     */
    public function isInstalled()
    {
        $installed_plugins = get_plugins();
        return isset($installed_plugins[$this->basename ]  );
    }

    /**
     * Activate Plugin
     */
    public function activate()
    {
        activate_plugin( $this->basename );
    }

    /**
     * Is plugin active?
     *
     * @return bool
     */
    public function isActive()
    {
        $plugins = get_option( 'active_plugins', array() );

        return is_array( $plugins ) && in_array( $this->basename, $plugins );
    }

    public function delete()
    {
        delete_plugins([$this->basename]);
    }

    protected function runCommand( $command ){

        $pusher = Pusher::getInstance();

        $dashboard = $pusher->make("Pusher\Dashboard");

        return $dashboard->execute($command);
    }

    /**
     * @param $branch
     * @return array
     */
    protected function pluginArgs($branch)
    {
        return [
            'repository' => $this->repo,
            'branch' => $branch,
            'type' => $this->type
        ];
    }
}