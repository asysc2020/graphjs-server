<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphJS;

use Pho\Kernel\Kernel;
use PhoNetworksAutogenerated\{User, Site};
use CapMousse\ReactRestify\Http\Session;

/**
 * The async/event-driven REST server daemon
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Daemon extends \Pho\Server\Rest\Daemon
{
    public function __construct()
    {
        $this->server = new Server();
//        $this->server->setAccessControlAllowOrigin("*");
        $this->initKernel();
        $this->initControllers(__DIR__);
        Router::init2($this->server, $this->controllers, $this->kernel);
    }

    protected function initKernel(): void
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__ . '/../../');
        $dotenv->load();
        $configs = array(
            "services"=>array(
                "database" => ["type" => getenv('DATABASE_TYPE'), "uri" => getenv('DATABASE_URI')],
                "storage" => ["type" => getenv('STORAGE_TYPE'), "uri" =>  getenv("STORAGE_URI")],
                "index" => ["type" => getenv('INDEX_TYPE'), "uri" => getenv('INDEX_URI')]
            ),
            "default_objects" => array(
                    "graph" => \PhoNetworksAutogenerated\Site::class,
                    "founder" => \PhoNetworksAutogenerated\User::class,
                    "actor" => \PhoNetworksAutogenerated\User::class
            )
        );
        $this->kernel = new \Pho\Kernel\Kernel($configs);
        $founder = new \PhoNetworksAutogenerated\User($this->kernel, $this->kernel->space(), "EmreSokullu", "esokullu@gmail.com", "123456");
        $this->kernel->boot($founder);
    }

}

