<?php namespace Orchestra\Foundation\Presenter\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Foundation\Presenter\Presenter;

class PresenterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        Facade::clearResolvedInstances();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Presenter\AbstractablePresenter::handles()
     * method.
     *
     * @test
     */
    public function testHandlesMethod()
    {
        $app = m::mock('\Illuminate\Container\Container', '\Illuminate\Contracts\Foundation\Application');
        $orchestra = m::mock('\Orchestra\Foundation\Foundation[handles]', [$app]);

        Container::setInstance($app);

        $app->shouldReceive('make')->once()->with('orchestra.app')->andReturn($orchestra);

        $orchestra->shouldReceive('handles')->with(m::type('String'), m::type('Array'))
                ->andReturnUsing(function ($s) {
                    return "foobar/{$s}";
                });

        $stub = new PresenterStub;
        $this->assertEquals('foobar/hello', $stub->handles('hello'));
    }

    /**
     * Test Orchestra\Foundation\Presenter\AbstractablePresenter::setupForm()
     * method.
     *
     * @test
     */
    public function testSetupFormMethod()
    {
        $form = m::mock('\Orchestra\Html\Form\Grid')->makePartial();

        $form->shouldReceive('layout')->once()
            ->with('orchestra/foundation::components.form')->andReturnNull();

        $stub = new PresenterStub;
        $stub->setupForm($form);
    }
}

class PresenterStub extends Presenter
{
    //
}
