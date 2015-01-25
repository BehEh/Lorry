#!/usr/bin/env php
<?php
require 'bootstrap.php';

use Lorry\Command;
use Symfony\Component\Console\Application;

$application = new Application('Lorry');
$application->addCommands(array(new Command\CacheClearer, new Command\CacheWarmer));
$application->run();