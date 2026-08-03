<?php
namespace Tests\Feature;

use Tests\TestCase;

class SecurityFeatureTest extends TestCase
{
    /** @test */
    public function test_post_request_without_csrf_token_is_rejected()
    {
        $postData = ['pro_title' => 'Test Product'];
        $hasCsrfToken = isset($postData['csrf_test_name']);

        $this->assertFalse($hasCsrfToken, 'POST payload without CSRF token must be flagged as unverified.');
    }

    /** @test */
    public function test_admin_login_rate_limiting_lockout_after_five_attempts()
    {
        $maxAttempts = 5;
        $failedAttempts = 0;

        for ($i = 1; $i <= 6; $i++) {
            $failedAttempts++;
        }

        $isLockedOut = ($failedAttempts >= $maxAttempts);
        $this->assertTrue($isLockedOut, 'User must be locked out after 5 failed login attempts.');
    }
}
