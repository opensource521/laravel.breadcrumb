<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;

class DependantServiceProviderReversedTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            // These are in the wrong order, but still work because of the deferred loading
            DependantServiceProviderError::class,
            BreadcrumbsServiceProvider::class,
        ];
    }

    protected function loadServiceProvider()
    {
        // Disabled - we want to test the automatic loading instead
    }

    public function testRender()
    {
        $html = Breadcrumbs::render('home')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Home</li>
            </ol>
        ', $html);
    }
}

class DependantServiceProviderError extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', '/');
        });
    }
}
