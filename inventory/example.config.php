<?php
/**
 * Configure settings that will be applied to any servers in this a group.
 */
$groups = array(
  'development' => array(
    'administrators' => array('spenser', 'chad', 'ben', 'mike', 'johan'),
    'management_ips' => array('10.0.0.0/24'),
  ),

  'production' => array(
    'administrators' => array('spenser', 'johan', 'ben'),
    'ssh_allow_nonadmins' => array('automatedbackups'),
    'management_ips' => array('184.71.62.238'),
  ),
);

/**
 * Configure individual servers.
 */
$config = array(
  'example.com' => array(
    'webserver' => true,
    'database' => true,
    'environment' => 'production',

    'websites' => array(
      '__hostname__' => array(
        'git' => array(
          'repo' => 'git@gitlab.com:simple-simple-advertising/bowvalleycollege.git',
        ),
        'type' => 'drupal7',
      ),
    ),
  ),

  'localdev' => array(
    'hostname' => 'local.dev',
    'webserver' => true,
    'database' => true,
    'environment' => 'development',
  ),
);
