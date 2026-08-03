<?php
namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static $db;
    protected $CI;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        
        // Initialize DB connection for testing if MySQL is running
        try {
            $mysqli = @new \mysqli('127.0.0.1', 'root', '', 'eshopper');
            if (!$mysqli->connect_error) {
                self::$db = $mysqli;
            }
        } catch (\Throwable $e) {
            self::$db = null;
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        if (self::$db) {
            self::$db->begin_transaction();
        }
    }

    protected function tearDown(): void
    {
        if (self::$db) {
            self::$db->rollback();
        }
        parent::tearDown();
    }

    /**
     * Helper to calculate order subtotal, tax, shipping, and total grand total
     */
    protected function calculateOrderTotal(array $items, float $taxRate = 0.02, float $shippingThreshold = 30.0, float $shippingFee = 15.0): array
    {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += (float)$item['price'] * (int)$item['qty'];
        }

        $tax = round($subtotal * $taxRate, 2);
        $shipping = ($subtotal >= $shippingThreshold) ? 0.0 : $shippingFee;
        $grandTotal = round($subtotal + $tax + $shipping, 2);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'grand_total' => $grandTotal
        ];
    }
}
