<?php

class PostNotificationTest extends \Codeception\Test\Unit
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

    public function testPostNotificationLoading()
    {
        $this->loadPluginator();

        $post_notification = new \Pauple\Pluginator\PostNotification();

        $method_exists = method_exists($post_notification, 'init');
        $this->assertTrue($method_exists);
    }



    private function loadPluginator()
    {
        $pluginator_path = dirname(dirname(dirname(__FILE__))) . '/pluginator.php';
        // error_log("pluginator_path: "  . $pluginator_path);
        require_once $pluginator_path;
    }
}
