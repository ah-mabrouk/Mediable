<?php

namespace JohnDoe\BlogPackage\Tests;

use Mabrouk\Mediable\MediableServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    // additional setup
  }

  protected function getPackageProviders($app)
  {
    return [
      MediableServiceProvider::class,
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
    // perform environment setup
  }
}
