<?php

namespace Tests\Feature\SystemInfo;

use App\Http\Livewire\SystemInfo\DiskUsage;
use App\Services\SystemInfo\DiskUsageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Fakes\FakeDiskUsageService;
use Tests\TestCase;

class DiskUsageTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_renders_on_page()
    {
        $user = $this->loginAsUser();

        $this->actingAs($user)
            ->get(route('system_info.index'))
            ->see('Disk Usage');
    }

    public function test_disk_usage_initial_state_is_loading()
    {
        Livewire::test(DiskUsage::class)
            ->assertSet('isLoading', true)
            ->assertSee('spinner.gif');
    }

    public function test_get_disk_usage_populates_expected_values()
    {
        $this->app->instance(DiskUsageService::class, new FakeDiskUsageService);

        Livewire::test(DiskUsage::class)
            ->call('getDiskUsage')
            ->assertSet('isLoading', false)
            ->assertSet('diskUsage', '1GB')
            ->assertSet('diskQuota', '2GB')
            ->assertSet('diskUsageInPercent', 50)
            ->assertSet('percentColor', 'info')
            ->assertDontSee('spinner.gif')
            ->assertSee('50%')
            ->assertSee('1GB of 2GB');
    }

    public function test_percent_color_logic()
    {
        $component = new DiskUsage;

        $this->assertEquals('success', $this->invokeMethod($component, 'getPercentColor', [10]));
        $this->assertEquals('info', $this->invokeMethod($component, 'getPercentColor', [30]));
        $this->assertEquals('warning', $this->invokeMethod($component, 'getPercentColor', [60]));
        $this->assertEquals('danger', $this->invokeMethod($component, 'getPercentColor', [90]));
    }

    // Helper to access protected methods
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
