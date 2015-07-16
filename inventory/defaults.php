<?php

function getHostDefaults($type, $host) {
  global $generic_host, $default_host_types, $groups;
  $default_group = empty($default_host_types[$type]) ? array() : $default_host_types[$type];
  $custom_group = empty($groups[$type]) ? array() : $groups[$type];
  return array_replace_recursive($default_group, $custom_group, $host);
}

$config = array(
  '_meta' => array(
  ),
);

$generic_host = array(
  'administrators' => array(),
  'ssh_allow_nonadmins' => array(),
  'management_ips' => array(),
);

$default_host_types = array(
  'development' => array(
    'websites' => array(
      '__hostname__' => array(
        'git' => array(
          'repo' => 'git@gitlab.com:simple-simple-advertising/devbox-landing-page.git',
        ),
      ),
    ),
    'nginx' => true,
    'haproxy' => false,
    'varnish' => false,
  ),

  'production' => array(
    'nginx' => true,
    'nginx_port' => 8081,
    'nginx_spdy_port' => 8082,

    'haproxy' => true,
    'haproxy_port' => 80,
    'haproxy_ssl_port' => 443,

    'varnish' => true,
    'varnish_port' => 8080,
  ),

  'webserver' => array(
    'nginx_host' => '127.0.0.1',
    'websites' => array(
      'phpmyadmin.__hostname__' => array(
        'git' => array(
          'repo' => 'https://github.com/phpmyadmin/phpmyadmin.git',
          'branch' => 'MAINT_4_0_10',
        ),
        'type' => 'php',
        'nginx_tweak' => 'client_max_body_size 1024m;',
      ),
    ),
  ),

  'database' => array(
    'database' => array(
      'exposed' => FALSE,
    ),
  ),
);
