<?php
namespace GSMail;

use GSMail\Service\GSMailManager;
use GSMail\Service\GSMailManagerFactory;

return [
    'service_manager' => [
		'factories' => [
			GSMailManager::class => GSMailManagerFactory::class,
    	],
	],
];