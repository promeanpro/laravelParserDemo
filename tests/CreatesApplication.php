<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Get the path to the storage testing folder.
     *
     * @param string $path
     * @return string
     */
    public static function getTestingPath($path = '')
    {
        return rtrim(implode(DIRECTORY_SEPARATOR, [
            dirname(dirname(__FILE__)),
            'storage',
            'framework',
            'testing',
            $path
            ]), DIRECTORY_SEPARATOR);
    }
}
