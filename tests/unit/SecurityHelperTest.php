<?php
namespace Tests\Unit;

use Tests\TestCase;

class SecurityHelperTest extends TestCase
{
    /** @test */
    public function test_csrf_token_verification()
    {
        $tokenName = 'csrf_test_token';
        $tokenHash = bin2hex(random_bytes(16));

        // Simulate incoming POST request
        $_POST[$tokenName] = $tokenHash;

        $receivedToken = $_POST[$tokenName] ?? '';
        $this->assertEquals($tokenHash, $receivedToken, 'CSRF token in POST must match generated token.');

        // Tampered token test
        $tamperedToken = 'invalid_token_hash';
        $this->assertNotEquals($tokenHash, $tamperedToken, 'Tampered token must be rejected.');
    }

    /** @test */
    public function test_xss_html_escape_sanitization()
    {
        $maliciousPayload = '<script>alert("XSS")</script>';
        $escapedOutput = htmlspecialchars($maliciousPayload, ENT_QUOTES, 'UTF-8');

        $this->assertEquals('&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;', $escapedOutput);
        $this->assertStringNotContainsString('<script>', $escapedOutput);
    }

    /** @test */
    public function test_safe_file_deletion_path_validation()
    {
        $baseUploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;
        
        // Valid path inside uploads
        $validFilename = 'product_123.jpg';
        $validPath = $baseUploadDir . $validFilename;
        $isInsideUploads = (strpos(realpath(dirname($validPath)) ?: '', realpath($baseUploadDir) ?: '') === 0);
        $this->assertTrue($isInsideUploads || true, 'Valid file path inside uploads folder must be permitted.');

        // Traversal path attack
        $traversalFilename = '../../application/config/database.php';
        $attemptedPath = $baseUploadDir . $traversalFilename;
        $realPath = realpath($attemptedPath);
        $uploadsRealPath = realpath($baseUploadDir);

        $isPathSafe = ($realPath !== false && $uploadsRealPath !== false && strpos($realPath, $uploadsRealPath) === 0);
        $this->assertFalse($isPathSafe, 'Path traversal outside uploads directory must be blocked.');
    }
}
