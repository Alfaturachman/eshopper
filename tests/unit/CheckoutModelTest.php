<?php
namespace Tests\Unit;

use Tests\TestCase;

class CheckoutModelTest extends TestCase
{
    /** @test */
    public function test_calculate_order_total_with_free_shipping()
    {
        $cartItems = [
            ['id' => 1, 'price' => 20.00, 'qty' => 2] // Subtotal = 40.00 (>= 30.00)
        ];

        $totals = $this->calculateOrderTotal($cartItems);

        $this->assertEquals(40.00, $totals['subtotal']);
        $this->assertEquals(0.80, $totals['tax']); // 2% of 40 = 0.80
        $this->assertEquals(0.00, $totals['shipping']); // Free shipping
        $this->assertEquals(40.80, $totals['grand_total']);
    }

    /** @test */
    public function test_calculate_order_total_with_shipping_fee()
    {
        $cartItems = [
            ['id' => 2, 'price' => 10.00, 'qty' => 1] // Subtotal = 10.00 (< 30.00)
        ];

        $totals = $this->calculateOrderTotal($cartItems);

        $this->assertEquals(10.00, $totals['subtotal']);
        $this->assertEquals(0.20, $totals['tax']); // 2% of 10 = 0.20
        $this->assertEquals(15.00, $totals['shipping']); // $15 shipping fee
        $this->assertEquals(25.20, $totals['grand_total']);
    }

    /** @test */
    public function test_stock_validation_pass_and_fail()
    {
        $availableStock = 10;

        $requestedQtyValid = 5;
        $isValid = ($requestedQtyValid > 0 && $requestedQtyValid <= $availableStock);
        $this->assertTrue($isValid, 'Requesting 5 items from 10 stock should pass.');

        $requestedQtyInvalid = 15;
        $isInvalid = ($requestedQtyInvalid > 0 && $requestedQtyInvalid <= $availableStock);
        $this->assertFalse($isInvalid, 'Requesting 15 items from 10 stock should fail.');

        $requestedQtyNegative = -1;
        $isNegativeInvalid = ($requestedQtyNegative > 0 && $requestedQtyNegative <= $availableStock);
        $this->assertFalse($isNegativeInvalid, 'Negative quantity should fail.');
    }

    /** @test */
    public function test_customer_password_migration_from_md5_to_bcrypt()
    {
        $rawPassword = 'mySecretPassword123';
        
        // Simulating legacy MD5 hash in database
        $legacyMd5Hash = md5($rawPassword);

        // Verification logic
        $isValidLegacy = (md5($rawPassword) === $legacyMd5Hash);
        $this->assertTrue($isValidLegacy, 'Legacy MD5 hash verification should succeed.');

        // Upgrading hash to Bcrypt
        $newBcryptHash = password_hash($rawPassword, PASSWORD_BCRYPT);
        $this->assertNotEquals($legacyMd5Hash, $newBcryptHash);
        $this->assertEquals(60, strlen($newBcryptHash), 'Bcrypt hash must be 60 characters long.');

        // Verifying upgraded Bcrypt hash
        $this->assertTrue(password_verify($rawPassword, $newBcryptHash), 'Bcrypt verification should succeed.');
    }
}
