<?php
/**
 * Opauth basic configuration file to quickly get you started
 * ==========================================================
 * To use: rename to opauth.conf.php and tweak as you like
 * If you require advanced configuration options, refer to opauth.conf.php.advanced
 */

$config = array(
/**
 * Path where Opauth is accessed.
 *  - Begins and ends with /
 *  - eg. if Opauth is reached via http://example.org/auth/, path is '/auth/'
 *  - if Opauth is reached via http://auth.example.org/, path is '/'
 */
	'path' => '/opauth/',

/**
 * Callback URL: redirected to after authentication, successful or otherwise
 */
	'callback_url' => '{path}callback.php',

/**
 * A random string used for signing of $auth response.
 */
	'security_salt' => 'lDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m',

/**
 * Strategy
 * Refer to individual strategy's documentation on configuration requirements.
 *
 * eg.
 * 'Strategy' => array(
 *
 *   'Facebook' => array(
 *      'app_id' => 'APP ID',
 *      'app_secret' => 'APP_SECRET'
 *    ),
 *
 * )
 *
 */
	'Strategy' => array(
		// Define strategies and their respective configs here
		'yahoojp' => array(
			'client_id' => 'dj0zaiZpPVEwYVZKdHcyaUc3UCZzPWNvbnN1bWVyc2VjcmV0Jng9NDk-',
			'client_secret' => '9f5f72b448fba98702c112ce8dd1d0807d40ec4e'
		)
	),

/**
 * Directory where Opauth strategies reside
 */
	// 'strategy_dir' => dirname(dirname(__FILE__)) . '/y-connect/vendor/opauth/',
);
