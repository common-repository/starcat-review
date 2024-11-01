<?php

class UpgraderTest extends \Codeception\Test\Unit

{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testUpgraderLoading()
    {
        $project_root = dirname(dirname(dirname(__FILE__)));
        // $loader = require $project_root . '/vendor/autoload.php';
        // $loader->addPsr4('Pluginator\\Test\\', __DIR__);

        $this->loadPluginator();

        $args = [
            'db_version' => '0.3.9',
            'file_version' => '0.5.9.2',
            'version_option' => 'helpie_upgrader_version',
            'slug' => 'helpie_upgrader',
        ];

        require_once $project_root . '/examples/upgrade-example/upgrades-list.php';
        $upgrades_list = new \Pluginator\Examples\Upgrade_Example\Upgrade_List();
        $upgrader = new \Pauple\Pluginator\Upgrader($args, $upgrades_list);

        $method_exists = method_exists($upgrader, 'init');
        $this->assertTrue($method_exists);
    }

    private function loadPluginator()
    {
        $pluginator_path = dirname(dirname(dirname(__FILE__))) . '/pluginator.php';
        // error_log("pluginator_path: " . $pluginator_path);
        require_once $pluginator_path;
    }
}
