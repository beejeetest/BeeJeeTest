<?php

namespace App;

class Db
{
    public function __construct()
    {


        $settings = $this->getPDOSettings();
        \ActiveRecord\Config::initialize(function ($cfg) use ($settings) {
            $cfg->set_model_directory(MODELS_PATH);
            $cfg->set_connections([
                ENVIRONMENT_DEV => $settings->{ENVIRONMENT_DEV},
                ENVIRONMENT_PROD => $settings->{ENVIRONMENT_PROD},
            ]);
            $cfg->set_default_connection(ENVIRONMENT);
        });
    }

    protected function getPDOSettings()
    {
        $config = include CONFIG_PATH . DIRECTORY_SEPARATOR . 'Db.php';
        $configDev = $config[ENVIRONMENT_DEV];
        $configProd = $config[ENVIRONMENT_PROD];
        return (object)[
            ENVIRONMENT_DEV => "{$configDev['driver']}://{$configDev['user']}:{$configDev['pass']}@{$configDev['host']}/{$configDev['dbname']}?charset={$configDev['charset']}",
            ENVIRONMENT_PROD => "{$configProd['driver']}://{$configProd['user']}:{$configProd['pass']}@{$configProd['host']}/{$configProd['dbname']}?{$configProd['charset']}",
        ];
    }
}
