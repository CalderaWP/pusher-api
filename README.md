Install plugins from Github/Gitlab/Bitbucket via REST API request. Requires [WP_Pusher](https://wppusher.com).

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

### Authorization
By default, you __no one is authorized__ to use these endpoints.

Use the `caldera_pusher_api_request_allow` to control authorization.

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

