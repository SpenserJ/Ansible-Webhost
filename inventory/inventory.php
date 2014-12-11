#!/usr/bin/env php
<?php
require 'config.php';

if (isset($config_raw) === false || is_array($config_raw) === false) {
  $config_raw = array();
}

$config_raw = $config;

require 'defaults.php';

$options = getopt('v', array('host:', 'list'));

$pretty = (isset($options['v']) === true) ? JSON_PRETTY_PRINT : NULL;

if (empty($options['host']) === false) {
  $host = $options['host'];

  if (empty($config_raw[$host]) === true) {
    echo json_encode(array());
    return;
  }
  echo json_encode(processHostVars($host, $config_raw[$host]), $pretty);
  return;
}

function processHostVars($host, $settings) {
  global $config;

  // Is this server a webserver?
  if (isset($settings['webserver']) === true && $settings['webserver'] === true) {
    $config['webserver']['hosts'][] = $host;
    $settings = getHostDefaults('webserver', $settings);
  }

  // Is this server a database?
  if (isset($settings['database']) === true && $settings['database'] === true) {
    $config['database']['hosts'][] = $host;
    $settings = getHostDefaults('database', $settings);
  }

  // Set this into the correct environment hostgroup
  if (empty($settings['environment']) === false) {
    $config[$settings['environment']]['hosts'][] = $host;
    $settings = getHostDefaults($settings['environment'], $settings);
  }

  // Ensure this host's hostname is set
  if (empty($settings['hostname']) === true) {
    $settings['hostname'] = $host;
  }

  foreach ($settings['websites'] as $domain => &$website) {
    // If the host config has disabled this site, remove it
    if ($website === false) {
      unset($settings['websites'][$domain]);
    }

    // Handle any hostname replacement on the domain name
    $website['domain'] = str_replace('__hostname__', $host, $domain);

    // Set a user for this site to run as
    $website['user'] = substr($website['domain'], 0, 32);

    // Ensure an SSL variable is set
    if (isset($website['ssl']) === false) {
      $website['ssl'] = false;
    }
  }
  $settings['websites'] = array_values($settings['websites']);

  return $settings;
}

foreach ($config_raw as $host => $value) {
  $config['_meta']['hostvars'][$host] = processHostVars($host, $value);
}

if (isset($options['list']) === true) {
  unset($config['_meta']);
}

echo json_encode($config, $pretty);
