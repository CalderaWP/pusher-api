Install plugins from Github/Gitlab/Bitbucket via REST API request. Requires [WP_Pusher](https://wppusher.com).

[<strong>You probably should not use this</strong>. Seriously, I'm using this for automated acceptance testing. It's going to run on sites that exist for a 5 minutes at a time. There is an authorization filter, that is false by default, but still, think this one through -- you can set this to allow remote installation of WordPress plugins from any Github/lab or Bitbucket repo.](#Security)
## Endpoint
This plugin adds one endpoint `/wp-json/caldera/pusher/v1/plugin`.

* Use a DELETE request to delete plugin.
* All other methods will <strong>delete the plugin</strong> if installed and then install it.
* Install Caldera Forms using the develop branch and activate it.
    - `https://caldera-dev.lndo.site/wp-json/caldera/pusher/v1/plugin?repo=calderawp/caldera-forms&branch=develop&basename=caldera-forms/caldera-core.phphttps://caldera-dev.lndo.site/wp-json/caldera/pusher/v1/plugin?repo=calderawp/caldera-forms&branch=develop&basename=caldera-forms/caldera-core.php`

Args: 

```json
{
  "args": {
    "repo": {
      "required": true,
      "description": "Repo name: vendor\/repo",
      "type": "string"
    },
    "type": {
      "required": false,
      "default": "gh",
      "description": "Type of repo: gh|bb|gl",
      "type": "string"
    },
    "branch": {
      "required": false,
      "default": "master",
      "description": "Branch to install",
      "type": "string"
    },
    "basename": {
      "required": false,
      "description": "Plugin basename",
      "type": "string"
    },
    "activate": {
      "required": false,
      "default": false,
      "description": "Activate plugin?",
      "type": "bool"
    }
  }
}
```
### Security
#### Authorization
By default, __no one is authorized__ to use these endpoints.

Use the `caldera_pusher_api_request_allow` to control authorization. Return true to allow the endpoint to be authorize, return false to not allow.

For example, you may want to create a user that has no special capabilities, for example a regular subscriber. Then only requests authorized as that user to use this endpoint.

```php
<?php
/**
 * Only allow the user with the ID of 42 to use this endpoint
 */
add_filter( 'caldera_pusher_api_request_allow', function($allow){
    if( get_current_user_id() ){
        $user = get_user_by( 'ID', get_current_user_id() );
        return $user->ID = 42;
    }
    return $allow;
});
````
#### Whitelisting Plugins


```php
<?php
/**
 * Limit the plugins that can be used
 */
add_filter( 'caldera_pusher_api_request_args', function($args){
    //Add validation on "repo" arg so only a specific plugin can be installed
    $args[ 'repo' ][ 'validate_callback' ] = function($param, $request, $key) {
        return in_array( $param, [
            'calderawp/caldera-forms'
        ]);
    };

    //Add validation on "basename" arg so only specific plugins can be activated
    $args[ 'basename' ][ 'validate_callback' ] = function($param, $request, $key) {
        return in_array( $param, [
            'caldera-forms/caldera-core.php'
        ]);
    };
    return $args;
} );
```
## Development

### Install
Requires git and Composer


### Tests

#### Install
* Install all tests besides integration tests
   - They were installed automatically by Composer
* Install Integration Tests
    - Switch to bin
    - `cd bin`
    - Install tests (this SHOULD erase your database)
    - `bash ./install-wp-tests local root root`
    - This command assumes your database is called "local", and has a user "root" with this password of "root"

#### Use
Run these tests from the plugin's root directory, unless directed otherwise.
* Run Tests and Code Sniffs
    - `composer test`
* Run Unit Tests and Integration Tests
    - `composer tests`
* Run Unit Tests
    - `composer unit-tests`
* Fix all code formatting
    - `composer formatting`
* Integration tests (also runs unit tests)
    * Run these tests from the `bin` directory
    * `bash run-tests.sh`

