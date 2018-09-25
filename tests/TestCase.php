<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://snaphrm.local/';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
    public static function setFactory($module)
    {
        $factory = Illuminate\Database\Eloquent\Factory::construct(\Faker\Factory::create(), base_path() . '/modules/'.$module.'/Database/Factories');
        return $factory;
    }
}
