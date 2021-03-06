<?php namespace Orchestra\Foundation\Routing\Account\TestCase;

use Mockery as m;
use Orchestra\Testing\TestCase;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\Messages;
use Orchestra\Support\Facades\Foundation;

class PasswordUpdaterControllerTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        View::shouldReceive('share')->once()->with('errors', m::any());
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /**
     * Test GET /admin/account
     *
     * @test
     */
    public function testGetEditAction()
    {
        $this->getProcessorMock()->shouldReceive('edit')->once()
            ->with(m::type('\Orchestra\Foundation\Routing\Account\PasswordUpdaterController'))
            ->andReturnUsing(function ($listener) {
                return $listener->showPasswordChanger([]);
            });

        View::shouldReceive('make')->once()
            ->with('orchestra/foundation::account.password', [], [])->andReturn('show.password.changer');

        $this->call('GET', 'admin/account/password');
        $this->assertResponseOk();
    }

    /**
     * Test POST /admin/account
     *
     * @test
     */
    public function testPostUpdateAction()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Routing\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->passwordUpdated([]);
            });

        Messages::shouldReceive('add')->once()->with('success', m::any())->andReturnNull();
        Foundation::shouldReceive('handles')->once()->with('orchestra::account/password', [])->andReturn('password');

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Test POST /admin/account with invalid user id.
     *
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testPostIndexActionGivenInvalidUserId()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Routing\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->abortWhenUserMismatched();
            });

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Test POST /admin/account with database error.
     *
     * @test
     */
    public function testPostIndexActionGivenDatabaseError()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Routing\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->updatePasswordFailed([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::account/password', [])->andReturn('password');
        Messages::shouldReceive('add')->once()->with('error', m::any())->andReturnNull();

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Test POST /admin/account with validation failed.
     *
     * @test
     */
    public function testPostIndexActionGivenValidationFailed()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Routing\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->updatePasswordFailedValidation([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::account/password', [])->andReturn('password');

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Test POST /admin/account with hash check failed.
     *
     * @test
     */
    public function testPostIndexActionGivenHashMissmatch()
    {
        $input = $this->getInput();

        $this->getProcessorMock()->shouldReceive('update')->once()
            ->with(m::type('\Orchestra\Foundation\Routing\Account\PasswordUpdaterController'), $input)
            ->andReturnUsing(function ($listener) {
                return $listener->verifyCurrentPasswordFailed([]);
            });

        Foundation::shouldReceive('handles')->once()->with('orchestra::account/password', [])->andReturn('password');

        $this->call('POST', 'admin/account/password', $input);
        $this->assertRedirectedTo('password');
    }

    /**
     * Get processor mock.
     *
     * @return \Orchestra\Foundation\Processor\Account\PasswordUpdater
     */
    protected function getProcessorMock()
    {
        $processor = m::mock('\Orchestra\Foundation\Processor\Account\PasswordUpdater');

        $this->app->instance('Orchestra\Foundation\Processor\Account\PasswordUpdater', $processor);

        return $processor;
    }

    /**
     * Get sample input.
     *
     * @return array
     */
    protected function getInput()
    {
        return [
            'id'               => '1',
            'current_password' => '123456',
            'new_password'     => 'qwerty',
        ];
    }
}
