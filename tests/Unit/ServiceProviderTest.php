<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Support\Facades\Blade;

/**
 * Tests for sp service
 *
 * Class spTest
 * @package Test\Services
 */
class ServiceProviderTest extends TestCase
{

    /**
     * Test to check if the currency is calculated correctly
     * for given payment method.
     *
     * @param string $bladeSnippet
     * @param float $expectedCode
     *
     * @AppServiceProvider boot
     */
    public function testCurrency()
    {
        $bladeSnippet = '@currency(300)';
        $expectedCode = "Rp. <?php echo number_format(300, 2, ',', '.'); ?>";

        $this->assertEquals($expectedCode, Blade::compileString($bladeSnippet));
    }
}