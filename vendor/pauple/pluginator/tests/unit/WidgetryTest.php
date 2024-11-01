<?php

class WidgetryTest extends \Codeception\Test\Unit
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
    public function testInterfaceMethods()
    {
        // $project_root = dirname(dirname(dirname(__FILE__)));

        $this->tester->loadPluginator();

        $widget_args = [
            'id' => 'starcat-review-comparison-table',
            'name' => 'Helpie Comparison Table',
            'description' => 'Comparison Table Widget',
            'icon' => 'fa fa-th-list', // Used by Elementor only
            'categories' => ['general-elements'], // Used by Elementor only
        ];
        $widgetry = new \Pauple\Pluginator\Widgetry($widget_args);

        $method_exists = method_exists($widgetry, 'register_widget');
        $this->assertTrue($method_exists);
    }
}
