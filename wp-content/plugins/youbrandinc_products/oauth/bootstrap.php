<?php

/**
 * Bootstrap the library
 */
require_once YBI_BASE_PATH . '/vendor/autoload.php';

/**
 * Setup error reporting
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Setup the timezone
 */
ini_set('date.timezone', 'Europe/Amsterdam');

/**
 * Create a new instance of the URI class with the current URI, stripping the query string
 */
$uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');

/**
 * Load the credential for the different services
 */
//require_once YBI_BASE_PATH . '/vendor/lusitanian/oauth/examples/init.example.php';
require_once 'init.php';