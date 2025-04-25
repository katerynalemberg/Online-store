<?php

// phpcs:ignoreFile

/**
 * @file
 * Default settings file.
 */

// Trusted hosts patterns.
$settings['trusted_host_patterns'] = [];

// Sync channel.
$sync_channel = 'sync';

$directory = dirname(__FILE__);
// Include Platform.sh settings if available.
if (getenv('PLATFORM_APPLICATION') && file_exists($directory . '/settings.platformsh.php')) {
  include($directory . '/settings.platformsh.php');
}

// Automatically generated include for settings managed by ddev.
if (getenv('IS_DDEV_PROJECT') == 'true') {
  $environment = 'local';
}
