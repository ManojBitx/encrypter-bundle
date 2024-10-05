<?php

namespace ManojX\EncrypterBundle;

/**
 * Interface EncrypterInterface
 *
 * This interface defines the methods for encrypting and decrypting data.
 */
interface EncrypterInterface
{
    /**
     * Encrypts the given data using AES-256-CBC with an optional salt.
     *
     * @param string $data The data to encrypt.
     * @param string|null $salt The salt to use for key derivation (optional).
     * @return string The encrypted data, encoded in Base64 format, including the IV.
     *
     * @throws \Exception If encryption fails.
     */
    public function encrypt(string $data, ?string $salt = null): string;

    /**
     * Decrypts the given encrypted data using AES-256-CBC with an optional salt.
     *
     * @param string $data The encrypted data to decrypt.
     * @param string|null $salt The salt to use for key derivation (optional).
     * @return string The original data.
     *
     * @throws \Exception If decryption fails or if the data is invalid.
     */
    public function decrypt(string $data, ?string $salt = null): string;

    /**
     * Generates a random encryption key.
     *
     * @param int $length The desired length of the encryption key (in bytes). Must be 16, 32, or 64 bytes.
     * @return string The generated random encryption key, represented as a hexadecimal string.
     *
     * @throws \InvalidArgumentException If the length is not valid.
     */
    public function generateEncryptionKey(int $length = 32): string;

    /**
     * Generates a random salt.
     *
     * @param int $length The desired length of the salt (in bytes). Must be 16, 32, or 64 bytes.
     * @return string The generated random salt, represented as a hexadecimal string.
     *
     * @throws \InvalidArgumentException If the length is not valid.
     */
    public function generateSalt(int $length = 16): string;
}
