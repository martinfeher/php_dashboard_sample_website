<?php

/**
     * Description: Project application strings
     *
     */

    require_once 'environment.php';
    $app['domain'] = $environment['app_env'] === 'local' ? $environment['domain']['local'] : $environment['domain']['production'];
