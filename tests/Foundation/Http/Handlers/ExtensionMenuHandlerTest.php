<?php namespace Orchestra\Foundation\Http\Handlers\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler;

class ExtensionMenuHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler::handle()
     * method with authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithAuthorizedUser()
    {
        $app = new Container();
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Contracts\Extension\Factory');
        $app['orchestra.app'] = $foundation = m::mock('\Orchestra\Contracts\Foundation\Foundation');
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['translator'] = $translator = m::mock('\Illuminate\Translator\Translator');
        $app['Orchestra\Contracts\Authorization\Authorization'] = $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');

        $acl->shouldReceive('can')->with('manage-orchestra')->once()->andReturn(true);
        $translator->shouldReceive('trans')->once()->with('orchestra/foundation::title.extensions.list')->andReturn('extensions');
        $foundation->shouldReceive('handles')->once()->with('orchestra::extensions')->andReturn('admin/extensions');
        $menu->shouldReceive('add')->once()->andReturnSelf()
            ->shouldReceive('title')->once()->with('extensions')->andReturnSelf()
            ->shouldReceive('link')->once()->with('admin/extensions')->andReturnNull();

        $stub = new ExtensionMenuHandler($app);
        $this->assertNull($stub->handle());
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler::handle()
     * method without authorized user.
     *
     * @test
     */
    public function testCreatingMenuWithoutAuthorizedUser()
    {
        $app = new Container();
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Contracts\Extension\Factory');
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['Orchestra\Contracts\Authorization\Authorization'] = $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');

        $acl->shouldReceive('can')->with('manage-orchestra')->once()->andReturn(false);

        $stub = new ExtensionMenuHandler($app);
        $this->assertNull($stub->handle());
    }

    /**
     * Test Orchestra\Foundation\Http\Handlers\ExtensionMenuHandler::handle()
     * method without `orchestra.extension` bound to container.
     *
     * @test
     */
    public function testCreatingMenuWithoutBoundDependencies()
    {
        $app = new Container();
        $app['orchestra.platform.menu'] = $menu = m::mock('\Orchestra\Widget\Handlers\Menu');
        $app['Orchestra\Contracts\Authorization\Authorization'] = $acl = m::mock('\Orchestra\Contracts\Authorization\Authorization');

        $stub = new ExtensionMenuHandler($app);
        $this->assertNull($stub->handle());
    }
}
