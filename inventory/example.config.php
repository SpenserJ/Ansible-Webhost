<?php
/**
 * Configure settings that will be applied to any servers in this a group.
 */
$groups = array(
  'development' => array(
    // Users that are allowed to manage these servers.
    // These users must have a matching public key in roles/ssh/files/keys.
    // eg: roles/ssh/files/keys/spenser.pub for spenser.
    'administrators' => array('spenser'),
    // Any IPs that are allowed to manage these servers.
    'management_ips' => array('10.0.0.0/24'),
  ),

  'production' => array(
    // Users that are allowed to manage these servers.
    // These users must have a matching public key in roles/ssh/files/keys.
    // eg: roles/ssh/files/keys/spenser.pub for spenser.
    'administrators' => array('spenser'),
    // Other users that are allowed to SSH in, and are not administrators.
    'ssh_allow_nonadmins' => array('automatedbackups'),
    // Any IPs that are allowed to manage these servers.
    'management_ips' => array('184.71.62.238'),
  ),
);

/**
 * Configure individual servers, keyed by the name you SSH to the server with.
 *
 * No options are required, and will default to deploying a barebones server.
 */
$config = array(
  'hosting.example.com' => array(
    // Does this server have a different hostname than you connect to it with?
    'hostname' => 'example.com',

    // Is this server a webserver?
    'webserver' => TRUE,
    // Does this server have a database server on it?
    'database' => FALSE,
    // Does this server have memcached on it?
    'memcached' => TRUE,
    // Does this server have haproxy on it?
    'haproxy' => TRUE,
    // Does this server have Varnish on it?
    'varnish' => TRUE,
    // Is this a Production or Development server?
    'environment' => 'production',

    // Configure ports for Haproxy, Varnish, and Nginx.
    'nginx' => true,
    'nginx_port' => 8081,
    'nginx_spdy_port' => 8082,
    'haproxy' => true,
    'haproxy_port' => 80,
    'haproxy_ssl_port' => 443,
    'varnish' => true,
    'varnish_port' => 8080,

    // Where can this haproxy server find its Nginx host?
    'nginx_host' => '127.0.0.1',

    // Override the users that are allowed to manage this server.
    // This has the same setup as the defaults in $groups.
    'administrators' => array('spenser', 'john'),
    // Override the IPs that are allowed to SSH to this server.
    'management_ips' => array('192.168.0.0/24'),
    // Override users that are allowed to SSH in, and are not administrators.
    'ssh_allow_nonadmins' => array('guest'),

    // Options for each website that is deployed to this server.
    'websites' => array(
      // __hostname__ will be replaced with the hostname of this server.
      // This allows for easily configuring subdomains.
      '__hostname__' => array(
        // Should this website be automatically cloned from a git repository?
        'git' => array(
          // The repository to clone. The public key in roles/ssh/files/keys
          // must have access to this repository.
          'repo' => 'git@gitlab.com:simple-simple-advertising/bowvalleycollege.git',
          // Which branch should be automatically checked out?
          'branch' => 'master',
        ),

        // What type of site is this? (drupal6, drupal7, wordpress, static, php)
        'type' => 'drupal7',
        // Which SSL certificate should be installed for this website?
        // This will automatically force all traffic through SSL.
        'ssl' => 'ssl_certs/example.com/example.com.crt',

        // Customize the Nginx Site configuration.
        'nginx_tweak' => 'client_max_body_size 64m;',

        // Customize the PHP configuration for this website.
        'php_tweak' => array(
          'php_admin_value[post_max_size] = 64M',
          'php_admin_value[upload_max_filesize] = 64M',
        ),
        // Customize the PHP Pool processes.
        'php_process_max_total' => 15,
        'php_process_initial' => 5,
        'php_process_min_spare' => 5,
        'php_process_max_spare' => 10,
        'php_process_max_requests' => 500,

        // Which path should HAProxy check to see if the server is online?
        'httpcheck' => '/install.php',
        // Should this site allow robots to crawl it?
        'allow_robots' => FALSE,

        // Should loadbalancing be enabled for this website?
        'loadbalanced' => TRUE,
      ),
    ),

    // Is this webserver loadbalanced with another server?
    // If this is enabled, all websites will need to exist on both servers.
    'loadbalancer' => array(
      '192.168.0.5',
      '192.168.0.6',
    ),

    // If memcached is installed, what servers should it be exposed to?
    // This will typically match the loadbalancer list.
    'memcached' => array(
      'exposed' => array(
        '192.168.0.5',
        '192.168.0.6',
      ),
    ),
  ),

  'db1.example.com' => array(
    'environment' => 'production',

    // database can be a boolean for basic DB support, or an array of
    // configuration options, which allows provisioning dbs, users, and
    // master/slave relationships.
    'database' => array(
      // What servers is this database exposed to? (array|bool)
      'exposed' => array(
        '192.168.0.5',
        '192.168.0.6',
      ),
      // Which users should be provisioned on this database server?
      'users' => array(
        array(
          'user' => 'example.com',
          'host' => '192.168.0.5',
          'password' => 'password1234forWeb1',
          'privileges' => 'example.com.*:ALL',
        ),
        array(
          'user' => 'example.com',
          'host' => '192.168.0.6',
          'password' => 'password1234forWeb2',
          'privileges' => 'example.com.*:ALL',
        ),
        array(
          'user' => 'read-example.com',
          'host' => '192.168.0.5',
          'password' => 'readonlyForWeb1',
          'privileges' => 'example.com.*:SELECT',
        ),
      ),
      // Which databases should be provisioned on this server?
      'databases' => array(
        'example.com',
      ),
    ),
    // If this is a MySQL master, configure the slaves that can access it.
    'mysql_master' => array(
      'slaves' => array(
        '192.168.0.11' => 'passwordForSlaveUser',
      ),
    ),
  ),

  'db2.example.com' => array(
    'webserver' => FALSE,
    'database' => array(
      // What servers is this database exposed to? (array|bool)
      'exposed' => array(
        '192.168.0.5',
        '192.168.0.6',
      ),
    ),
    // Configure this server as a MySQL slave.
    'mysql_slave' => array(
      'master' => '192.168.0.10',
      'password' => 'passwordForSlaveUser',
      // This ID should be >=2, and should increment for every additional slave.
      'id' => 2,
    ),
    'environment' => 'production',
  ),
);
