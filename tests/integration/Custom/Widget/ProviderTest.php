<?php

namespace Fulcrum\Tests\Integration\Custom\Widget;

use Fulcrum\Custom\Widget\ShortcodeProvider;
use Fulcrum\Custom\Widget\WidgetProvider;
use Fulcrum\Tests\Integration\IntegrationTestCase;
use Mockery;

class ProviderTest extends IntegrationTestCase
{
    protected $uniqueId;
    protected $config;
    protected $providerConfig;
    protected $fulcrumMock;

    public function setUp()
    {
        parent::setUp();

        $this->uniqueId         = 'Fulcrum\Tests\Integration\Custom\Widget\Stubs\FooWidget';
        $this->config           = [];
        $this->fulcrumMock      = Mockery::mock('Fulcrum\FulcrumContract');
    }

    public function testShouldQueue()
    {
        $provider = new WidgetProvider($this->fulcrumMock);
        $this->assertEquals([], $provider->queued);

        $provider->register([$this->uniqueId]);

        $this->assertEquals([$this->uniqueId], $provider->queued);
    }

    public function testShouldRegisterWidgetWithWordPress()
    {
        $provider = new WidgetProvider($this->fulcrumMock);
        $provider->register([$this->uniqueId]);

        $this->initFulcrum();
        $provider->registerWidgetsCallback();

        global $wp_widget_factory;
        $this->assertEquals($this->uniqueId, get_class($wp_widget_factory->widgets[$this->uniqueId]));
    }

    protected function initFulcrum()
    {
        $fulcrumAliasMock = Mockery::mock('alias:Fulcrum\Fulcrum');
        $fulcrumAliasMock
            ->shouldReceive('getFulcrum')
            ->andReturn($this->fulcrumMock);

        $this->fulcrumMock
            ->shouldReceive('has')
            ->andReturn(true);

        $this->fulcrumMock
            ->shouldReceive('get')
            ->andReturn($this->getConfig());
    }

    protected function getConfig()
    {
        $config       = require __DIR__ . '/fixtures/provider-config.php';
        $concrete     = $config[$this->uniqueId]['concrete'];
        $this->config = $concrete();
        return $this->config;
    }
}
