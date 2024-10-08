# Encrypter Symfony Bundle

## Purpose

The Encrypter Bundle provides a simple and secure way to encrypt and decrypt data using the AES-256-CBC encryption algorithm. This bundle is designed for Symfony applications, allowing developers to protect sensitive information with ease. With built-in support for key generation and salt handling, the Encrypter Bundle helps ensure that your data remains secure.

## Features

- **Secure Encryption**: Uses AES-256-CBC for data protection.
- **Key Management**: Supports random key and salt generation.
- **Easy Integration**: Seamlessly integrates with Symfony applications.

## Installation

To install the Encrypter Bundle, run the following command:

```bash
composer require manojx/encrypter-bundle
```

## Configuration

To configure the Encrypter Bundle, you need to create a configuration file. Follow these steps:

1. **Create the Configuration File**: Navigate to the `config/packages` directory of your Symfony project and create a new file named `encrypter.yaml`.

```bash
touch config/packages/encrypter.yaml
```

2. Add Configuration Content: Open the newly created encrypter.yaml file in your text editor and add the following content:

```yaml
encrypter:
  secret: 'your16-32-64key' # Replace with your actual secret key
```

Ensure that the secret key is either 16, 32, or 64 bytes long. This key will be used for encryption and decryption operations.

## Available Methods

The Encrypter Bundle provides several methods for encryption, decryption, key generation, and salt generation. Below is a list of each method along with a brief description:

### `encrypt(string $data, ?string $salt = null): string`

Encrypts the given data using the AES-256-CBC encryption algorithm.

- **Parameters**:
  - `string $data`: The plaintext data to encrypt.
  - `string|null $salt`: An optional salt to use for key derivation.

- **Returns**: The encrypted data, encoded in Base64 format, including the initialization vector (IV).

### `decrypt(string $data, ?string $salt = null): string`

Decrypts the given encrypted data.

- **Parameters**:
  - `string $data`: The encrypted data to decrypt.
  - `string|null $salt`: An optional salt that was used during encryption.

- **Returns**: The original plaintext data.

- **Throws**: An exception if decryption fails.

### `generateEncryptionKey(int $length = 32): string`

Generates a random encryption key.

- **Parameters**:
  - `int $length`: The desired length of the encryption key in bytes. Valid lengths are 16, 32, or 64 bytes. Defaults to 32 bytes.

- **Returns**: The generated random encryption key, represented as a hexadecimal string.

- **Throws**: An exception if the length is not valid.

### `generateSalt(int $length = 16): string`

Generates a random salt.

- **Parameters**:
  - `int $length`: The desired length of the salt in bytes. Valid lengths are 16, 32, or 64 bytes. Defaults to 16 bytes.

- **Returns**: The generated random salt, represented as a hexadecimal string.

- **Throws**: An exception if the length is not valid.

## Usage

To use the Encrypter Bundle in your Symfony application, you can inject the `EncrypterInterface` into your services or controllers. Here's an example of how you can encrypt and decrypt data:

```php
use ManojX\EncrypterBundle\EncrypterInterface;

class YourService
{
    private EncrypterInterface $encrypter;

    public function __construct(EncrypterInterface $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    public function encryptData(string $data): string
    {
        $salt = 'your-salt'; // Define your salt
        return $this->encrypter->encrypt($data, $salt);
    }

    public function decryptData(string $encryptedData): string
    {
        $salt = 'your-salt'; // Use the same salt for decryption
        return $this->encrypter->decrypt($encryptedData, $salt);
    }
}
```
