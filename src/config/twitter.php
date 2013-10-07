<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Twitter endpoint URLs
	|--------------------------------------------------------------------------
	|
	| These are the URLs that are used to access Twitter's REST API and oAuth
	| service.
	|
	*/

	'requestTokenUrl'   => 'https://api.twitter.com/oauth2/token',
	'authorizeUrl'      => 'https://api.twitter.com/oauth/authorize',
	'accessTokenUrl'    => 'https://api.twitter.com/oauth/access_token',
	'callbackUrl'       => 'http://testing.dev/twitter-api',
	'timelineSearchUrl' => 'https://api.twitter.com/1.1/statuses/user_timeline.json',

);
