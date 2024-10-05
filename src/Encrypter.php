<?php

namespace ManojX\EncrypterBundle;

class Encrypter implements EncrypterInterface
{
    private array $validKeyLengths = [16, 32, 64];

    private string $secret;

    /**
     *
     * @param string $secret The secret key to use for encryption. It must be 16, 32, or 64 bytes long.
     *
     * @throws \Exception If the OpenSSL library is not available.
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
        // Check if OpenSSL functions are available
        if (!function_exists('openssl_encrypt')) {
            throw new \Exception('The OpenSSL library is not available. Please install the OpenSSL extension for PHP.');
        }

        // Validate the length of the secret key
        if (!in_array(strlen($this->secret), $this->validKeyLengths, true)) {
            throw new \InvalidArgumentException('The secret key must be 16, 32, or 64 bytes long. Current length: ' . strlen($this->secret) . ' bytes.');
        }
    }

    /**
     * Encrypts the given data using AES-256-CBC with an optional salt.
     *
     * @param string $data The data to encrypt.
     * @param string|null $salt The salt to use for key derivation (optional).
     * @return string The encrypted data, encoded in Base64 format, including the IV.
     *
     * @throws \Exception If encryption fails.
     */
    public function encrypt(string $data, ?string $salt = null): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        $encryptionKey = $this->getEncryptionKey($salt);

        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $encryptionKey, 0, $iv);

        // Return the IV concatenated with the encrypted data, encoded in Base64
        return base64_encode($iv . $encryptedData);
    }

    /**
     * Decrypts the given encrypted data using AES-256-CBC with an optional salt.
     *
     * @param string $data The encrypted data to decrypt.
     * @param string|null $salt The salt to use for key derivation (optional).
     * @return string The original data.
     *
     * @throws \Exception If decryption fails or if the data is invalid.
     */
    public function decrypt(string $data, ?string $salt = null): string
    {
        $decodedData = base64_decode($data);

        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($decodedData, 0, $ivLength);
        $encryptedData = substr($decodedData, $ivLength);

        $encryptionKey = $this->getEncryptionKey($salt);

        $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $encryptionKey, 0, $iv);

        // Check if decryption was successful
        if ($decryptedData === false) {
            throw new \Exception('Decryption failed. Verify that the encrypted data is correct and that you are using the proper encryption key and salt.');
        }

        return $decryptedData;
    }

    /**
     * Generates the encryption key by validating the secret and incorporating the salt.
     *
     * @param string|null $salt The salt to use for key derivation (optional).
     * @return string The derived encryption key.
     *
     * @throws \InvalidArgumentException If the secret is not valid.
     */
    private function getEncryptionKey(?string $salt): string
    {
        $saltToUse = $salt !== null ? $salt : '';

        // Derive the encryption key using the secret and the salt
        return hash('sha256', $this->secret . $saltToUse, true);
    }

    /**
     * Generates a random encryption key.
     *
     * @param int $length The desired length of the encryption key (in bytes). Default is 32 bytes (256 bits).
     * @return string The generated random encryption key, represented as a hexadecimal string.
     *
     * @throws \InvalidArgumentException If the length is not valid.
     * @throws \Exception If the random bytes generation fails.
     */
    public function generateEncryptionKey(int $length = 32): string
    {
        if (!in_array($length, $this->validKeyLengths, true)) {
            throw new \InvalidArgumentException('The key length must be 16, 32, or 64 bytes.');
        }

        return bin2hex(random_bytes($length));
    }

    /**
     * Generates a random salt.
     *
     * @param int $length The desired length of the salt (in bytes). Default is 16 bytes.
     * @return string The generated random salt.
     *
     * @throws \InvalidArgumentException If the length is not valid.
     * @throws \Exception If the random bytes generation fails.
     */
    public function generateSalt(int $length = 16): string
    {
        if (!in_array($length, $this->validKeyLengths, true)) {
            throw new \InvalidArgumentException('The salt length must be 16, 32, or 64 bytes. Current length: ' . $length . ' bytes.');
        }

        return bin2hex(random_bytes($length)); // Return as hexadecimal
    }
}
