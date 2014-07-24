<?php
return array(
    
    'service_manager' => array(
        
        'invokables' => array(
            // Datasources
            'zfcDatagrid.examples.data.phpArray' => 'ZfcDatagridExamples\Data\PhpArray',
            'zfcDatagrid.examples.data.doctrine2' => 'ZfcDatagridExamples\Data\Doctrine2',
            'zfcDatagrid.examples.data.zendSelect' => 'ZfcDatagridExamples\Data\ZendSelect'
        )
    ),
    
    'controllers' => array(
        'invokables' => array(
            'ZfcDatagridExamples\Controller\Person' => 'ZfcDatagridExamples\Controller\PersonController',
            'ZfcDatagridExamples\Controller\PersonDoctrine2' => 'ZfcDatagridExamples\Controller\PersonDoctrine2Controller',
            'ZfcDatagridExamples\Controller\PersonZend' => 'ZfcDatagridExamples\Controller\PersonZendController',
            'ZfcDatagridExamples\Controller\Minimal' => 'ZfcDatagridExamples\Controller\MinimalController',
            'ZfcDatagridExamples\Controller\Category' => 'ZfcDatagridExamples\Controller\CategoryController'
        )
    ),
    
    'router' => array(
        'routes' => array(
            'ZfcDatagrid' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/zfcDatagrid',
                    'defaults' => array(
                        '__NAMESPACE__' => 'ZfcDatagridExamples\Controller',
                        'controller' => 'person',
                        'action' => 'bootstrap'
                    )
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        ),
                        
                        'may_terminate' => true,
                        'child_routes' => array(
                            'wildcard' => array(
                                'type' => 'Wildcard',
                                
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'wildcard' => array(
                                        'type' => 'Wildcard'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),
    
    'console' => array(
        'router' => array(
            'routes' => array(
                'datagrid-person' => array(
                    'options' => array(
                        'route' => 'datagrid person [--page=] [--items=] [--filterBys=] [--filterValues=] [--sortBys=] [--sortDirs=]',
                        'defaults' => array(
                            'controller' => 'ZfcDatagridExamples\Controller\Person',
                            'action' => 'console'
                        )
                    )
                ),
                
                'datagrid-category' => array(
                    'options' => array(
                        'route' => 'datagrid category [--page=] [--items=] [--filterBys=] [--filterValues=] [--sortBys=] [--sortDirs=]',
                        'defaults' => array(
                            'controller' => 'ZfcDatagridExamples\Controller\Category',
                            'action' => 'console'
                        )
                    )
                )
            )
        )
    ),
    
    /**
     * The ZF2 DbAdapter + Doctrine2 connection is must for examples!
     */
    'zfcDatagrid_dbAdapter' => array(
        'driver' => 'Pdo_Sqlite',
        'database' => __DIR__ . '/../src/ZfcDatagridExamples/Data/examples.sqlite'
    ),
    
    'doctrine' => array(
        'connection' => array(
            'orm_zfcDatagrid' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'charset' => 'utf8',
                    'path' => __DIR__ . '/../src/ZfcDatagridExamples/Data/examples.sqlite'
                )
            )
        ),
        
        'configuration' => array(
            'orm_zfcDatagrid' => array(
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'driver' => 'orm_zfcDatagrid',
                'generate_proxies' => true,
                'proxy_dir' => 'data/ZfcDatagrid/Proxy',
                'proxy_namespace' => 'ZfcDatagrid\Proxy',
                'filters' => array()
            )
        ),
        
        'driver' => array(
            'ZfcDatagrid_Driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/ZfcDatagridExamples/Entity'
                )
            ),
            
            'orm_zfcDatagrid' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    'ZfcDatagridExamples\Entity' => 'ZfcDatagrid_Driver'
                )
            )
        ),
        
        // now you define the entity manager configuration
        'entitymanager' => array(
            // This is the alternative config
            'orm_zfcDatagrid' => array(
                'connection' => 'orm_zfcDatagrid',
                'configuration' => 'orm_zfcDatagrid'
            )
        ),
        
        'eventmanager' => array(
            'orm_crawler' => array()
        ),
        
        'sql_logger_collector' => array(
            'orm_crawler' => array()
        ),
        
        'entity_resolver' => array(
            'orm_crawler' => array()
        )
    )
);
