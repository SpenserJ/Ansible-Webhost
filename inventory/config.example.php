<?php
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
