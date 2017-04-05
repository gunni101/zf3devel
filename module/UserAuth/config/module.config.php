<?php
namespace UserAuth;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use UserAuth\Frontend\Controller\RegisterController;
use UserAuth\Frontend\Controller\AuthController;
use UserAuth\Frontend\Controller\UserController;
use UserAuth\Frontend\Factory\RegisterControllerFactory;
use UserAuth\Frontend\Factory\AuthControllerFactory;
use UserAuth\Frontend\Factory\UserControllerFactory;
use UserAuth\Model\Service\UserManager;
use UserAuth\Model\Factory\UserManagerFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use UserAuth\Model\Service\AuthAdapter;
use UserAuth\Model\Factory\AuthAdapterFactory;
use UserAuth\Model\Service\AuthManager;
use UserAuth\Model\Factory\AuthManagerFactory;
use UserAuth\Model\Factory\AuthenticationServiceFactory;

return [
    'router' => [
        'routes' => [
            'register' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/register',
                    'defaults' => [
                        'controller' => RegisterController::class,
                        'action' => 'register'
                    ]
                ]
            ],
            'success' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/success',
                    'defaults' => [
                        'controller' => RegisterController::class,
                        'action' => 'success'
                    ]
                ]
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'login'
                    ]
                ]
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'logout'
                    ]
                ]
            ],
            'forgetPassword' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/forgetpassword',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'forgetPassword'
                    ]
                ]
            ],
        	'resetPassword' => [
        		'type' => Literal::class,
        		'options' => [
        			'route' => '/resetpassword',
        			'defaults' => [
        				'controller' => UserController::class,
        				'action' => 'resetPassword'
        			]
        		]
        	],
        	
        	'users' => [
        		'type' => Segment::class,
        		'options' => [
        			'route' => '/users[/:action[/:id]]',
        			'defaults' => [
        				'controller' => UserController::class,
        				'action' => 'forgetMessage',
        			],
        			'constraints' => [
        				'action' => '[a-zA-z][a-zA-Z0-9_-]*',
        				'id' => '[a-zA-Z0-9_-]*',
        			]
        		]
        	]
        ]
    ],
    
    'controllers' => [
        'factories' => [
            AuthController::class => AuthControllerFactory::class,
            RegisterController::class => RegisterControllerFactory::class,
        	UserController::class => UserControllerFactory::class
        ]
        
    ],
    
    'service_manager' => [
        'factories' => [
            UserManager::class => UserManagerFactory::class,
            AuthAdapter::class => AuthAdapterFactory::class,
            AuthManager::class => AuthManagerFactory::class,
            \Zend\Authentication\AuthenticationService::class => AuthenticationServiceFactory::class
        ]
    ],
    
    'view_manager' => [
        'template_map' => [
            'user-auth/frontend/register/register' => __DIR__ . '/../view/userauth/frontend/registerstep1.phtml',
            'user-auth/frontend/register/success' => __DIR__ . '/../view/userauth/frontend/registerstep2.phtml',
            'user-auth/frontend/auth/login' => __DIR__ . '/../view/userauth/frontend/login.phtml',
            'user-auth/frontend/user/forget-password' => __DIR__ . '/../view/userauth/frontend/forget.phtml',
        	'user-auth/frontend/user/forget-message' => __DIR__ . '/../view/userauth/frontend/forgetMessage.phtml',
        	'user-auth/frontend/user/reset-password' => __DIR__ . '/../view/userauth/frontend/resetPassword.phtml'
        ],
        'template_path_stack' => [
            __DIR__ . '/../view'
        ]
    ],
    
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];