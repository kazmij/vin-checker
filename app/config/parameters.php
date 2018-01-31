<?php

if ( getenv('NODE_HOME')){
    $node_home = getenv('NODE_HOME');
    $container->setParameter('node_executable', $node_home . '/bin/node');
}

if ( getenv('ASSETS_VERSION')){
    $version = getenv('ASSETS_VERSION');
    $container->setParameter('assets_version', $version);
}