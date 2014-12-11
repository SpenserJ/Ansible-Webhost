<?php

function getHostDefaults($type, $host) {
  global $default_host_types;
  if (empty($default_host_types[$type]) === true) { return $host; }
  return array_replace_recursive($default_host_types[$type], $host);
}

$config = array(
  '_meta' => array(
  ),
);

$default_host_types = array(
  'development' => array(
    'management_ips' => array('10.0.0.0/24'),
    'administrators' => array('spenser'),
    'websites' => array(
      '__hostname__' => array(
        'git' => array(
          'repo' => 'git@gitlab.com:simple-simple-advertising/devbox-landing-page.git',
        ),
      ),
    ),
    'nginx' => true,
    'apache' => false,
    'haproxy' => false,
    'varnish' => false,
  ),

  'production' => array(
    'management_ips' => array('184.71.62.238'),
    'administrators' => array('spenser'),
    'ssh_allow_nonadmins' => array('automatedbackups'),

    'apache' => false,
    'nginx' => true,
    'nginx_port' => 8081,
    'nginx_spdy_port' => 8082,

    'haproxy' => false,
    'haproxy_port' => 80,
    'haproxy_ssl_port' => 443,

    'varnish' => false,
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
);
