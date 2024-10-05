<?php

namespace ManojX\EncrypterBundle\Tests;

use ManojX\EncrypterBundle\Encrypter;
use PHPUnit\Framework\TestCase;

class EncrypterTest extends TestCase
{
    private Encrypter $encrypter;
    private string $secret = '1287dcd006996f149a5366e8caac03b4c2de6a0efcf56431275d4b4fb168e9e3';

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        // Initialize the Encrypter with a valid secret
        $this->encrypter = new Encrypter($this->secret);
    }

    public function testEncryptDecrypt()
    {
        $data = "Sensitive data to encrypt.";
        $salt = "randomsalt";

        // Encrypt the data
        $encryptedData = $this->encrypter->encrypt($data, $salt);

        // Decrypt the data
        $decryptedData = $this->encrypter->decrypt($encryptedData, $salt);

        // Assert that the decrypted data matches the original data
        $this->assertEquals($data, $decryptedData);
    }

    /**
     * @throws \Exception
     */
    public function testEncryptDecryptWithDifferentSalt()
    {
        $data = "Another sensitive data to encrypt.";
        $salt1 = "randomsalt1";
        $salt2 = "randomsalt2";

        // Encrypt with first salt
        $encryptedData = $this->encrypter->encrypt($data, $salt1);

        // Attempt to decrypt with different salt and expect an exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Decryption failed. Verify that the encrypted data is correct and that you are using the proper encryption key and salt.');

        // Attempt to decrypt with different salt (should throw an exception)
        $this->encrypter->decrypt($encryptedData, $salt2);
    }

    /**
     * @throws \Exception
     */
    public function testGenerateEncryptionKey()
    {
        // Generate a 32-byte encryption key
        $key = $this->encrypter->generateEncryptionKey(32);

        // Assert that the generated key is of correct length (64 characters in hex)
        $this->assertEquals(64, strlen($key));
    }

    /**
     * @throws \Exception
     */
    public function testGenerateSalt()
    {
        // Generate a 32-byte salt
        $salt = $this->encrypter->generateSalt(32);

        // Assert that the generated salt is of correct length (64 characters in hex)
        $this->assertEquals(64, strlen($salt));
    }

    public function testInvalidSecretKeyLength()
    {
        // Initialize with an invalid secret key (less than 16, 32, or 64 bytes)
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The secret key must be 16, 32, or 64 bytes long. Current length: 28 bytes.');

        new Encrypter('shortkey12345678901234567890');
    }

    /**
     * @throws \Exception
     */
    public function testInvalidSaltLength()
    {
        // Attempt to generate salt with invalid length
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The salt length must be 16, 32, or 64 bytes. Current length: 10 bytes.');

        $this->encrypter->generateSalt(10); // Invalid length
    }
}
