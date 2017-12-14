<?php

namespace Fulcrum\Tests\Integration\Custom\Widget;

use Fulcrum\Config\ConfigFactory;
use Fulcrum\Custom\Widget\Widget;
use Fulcrum\Tests\Integration\Custom\Widget\Stubs\FooWidget;
use Fulcrum\Tests\Integration\IntegrationTestCase;
use Mockery;

class WidgetTest extends IntegrationTestCase
{
    protected static $args = [
        'before_title'  => '<h2>',
        'after_title'   => "</h2>\n",
        'before_widget' => "<section>\n",
        'after_widget'  => '</section>',
    ];
    protected static $instance = [
        'title' => 'Foo',
        'text'  => 'Foo widget text.',
        'class' => 'foo-widget',
    ];
    protected $config;
    protected $fulcrumMock;
    protected $fulcrumAliasMock;

    public function setUp()
    {
        parent::setUp();

        $this->config           = ConfigFactory::create(__DIR__ . '/fixtures/foo-widget-config.php');
        $this->fulcrumMock      = Mockery::mock('Fulcrum\FulcrumContract');
        $this->fulcrumAliasMock = Mockery::mock('alias:Fulcrum\Fulcrum');
    }

    protected function initFulcrum()
    {
        $this->fulcrumAliasMock
            ->shouldReceive('getFulcrum')
            ->andReturn($this->fulcrumMock);

        $this->fulcrumMock
            ->shouldReceive('has')
            ->andReturn(true);

        $this->fulcrumMock
            ->shouldReceive('get')
            ->andReturn($this->config);
    }

    public function testShouldCreateWidget()
    {
        $this->initFulcrum();
        $widget = new Widget();
        $this->assertInstanceOf('Fulcrum\Custom\Widget\Widget', $widget);

        $this->assertEquals('foo-widget', $widget->id_base);
        $this->assertEquals('foo-widget', $widget->widget_options['classname']);
        $this->assertEquals(400, $widget->control_options['width']);
        $this->assertEquals(350, $widget->control_options['height']);
    }

    public function testShouldRenderWidget()
    {
        $this->initFulcrum();
        $widget = new Widget();

        ob_start();
        $widget->widget(self::$args, self::$instance);
        $widgetHTML = ob_get_clean();

        $this->assertEquals($this->renderFullWidget(), $widgetHTML);
    }

    public function testShouldRenderForm()
    {
        $this->initFulcrum();
        $widget = new Widget();

        ob_start();
        $widget->form(self::$instance);
        $this->assertEquals('<p>Foo widget text.</p>', ob_get_clean());
    }

    public function testFullIntegration()
    {
        $widgetClass = FooWidget::class;
        $this->initFulcrum();
        register_widget($widgetClass);

        ob_start();
        the_widget($widgetClass, self::$instance, self::$args);
        $widgetHTML = ob_get_clean();

        unregister_widget($widgetClass);

        $this->assertEquals($this->renderFullWidget(), $widgetHTML);
    }

    protected function renderFullWidget()
    {
        ob_start();
        require __DIR__ . '/fixtures/views/full-widget.html';
        return ob_get_clean();
    }
}
