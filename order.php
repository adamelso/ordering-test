#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use FeelUnique\Ordering\Command\OrderCommand;
use FeelUnique\Ordering\OrderApplication;
use FeelUnique\Ordering\DependencyInjection\OrderingExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;


$container = new ContainerBuilder();
$extension = new OrderingExtension();

$container->registerExtension($extension);
$container->loadFromExtension($extension->getAlias());

$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/src/Resources/config'));
$loader->load('config.yml');


$container->compile();

$application = new OrderApplication();
$application->setContainer($container);
$application->run();
