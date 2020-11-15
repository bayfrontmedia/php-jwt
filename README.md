## PHP-JWT

A simple library to encode and decode JSON Web Tokens (JWT) in PHP, conforming to [RFC 7519](https://tools.ietf.org/html/rfc7519).

Currently, the only supported algorithm is "HS256". 
Support for additional algorithms is planned for future versions.

- [License](#license)
- [Author](#author)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)

## License

This project is open source and available under the [MIT License](LICENSE).

## Author

John Robinson, [Bayfront Media](https://www.bayfrontmedia.com)

## Requirements

* PHP >= 7.1.0
* JSON PHP extension

## Installation

```
composer require bayfrontmedia/php-jwt
```

## Usage

A private, reproducible secret must be passed to the constructor. 
The same secret used to encode the JWT must also be used when decoding in order to verify the signature.

A cryptographically secure secret can be generated using the static `createSecret()` method, if needed.

```
use Bayfront\JWT\Jwt;

$secret = Jwt::createSecret(); // Be sure to save the secret to be used to decode the JWT

$jwt = new Jwt($secret);
```

### Public methods

- [createSecret](#createsecret)
- [getHeader](#getheader)
- [setHeader](#setheader)
- [removeHeader](#removeheader)
- [getPayload](#getpayload)
- [setPayload](#setpayload)
- [removePayload](#removepayload)
- [aud](#aud)
- [exp](#exp)
- [iat](#iat)
- [iss](#iss)
- [jti](#jti)
- [nbf](#nbf)
- [sub](#sub)
- [encode](#encode)
- [decode](#decode)
- [validateSignature](#validatesignature)
- [validateClaims](#validateclaims)

<hr />

### createSecret

**Description:**

Create a cryptographically secure secret of random bytes.

**NOTE:** Secrets are meant to be stored, as the same secret used to encode a JWT must be used to decode it.

**Parameters:**

- `$characters = 32` (int): Number of characters

**Returns:**

- (string)

**Throws:**

- `Exception`

**Example:**

```
use Bayfront\JWT\Jwt;

try {
    
    $secret = Jwt::createSecret();
    
} catch (Exception $e) {
    die($e->getMessage());
}
```

<hr />

### getHeader

**Description:**

Returns current header array.

**Parameters:**

- None

**Returns:**

- (array)

**Example:**

```
print_r($jwt->getHeader());
```

<hr />

### setHeader

**Description:**

Set custom value(s) to the current header array. 

**Parameters:**

- `$header` (array): Key/value pairs to set to the header array

**Returns:**

- (self)

**Example:**

```
$header = [
    'cty' => 'custom-content-type;v=1'
];

$jwt->setHeader($header);
```

<hr />

### removeHeader

**Description:**

Remove header key, if existing.

**Parameters:**

- `$key` (string)

**Returns:**

- (self)

**Example:**

```
$jwt->removeHeader('cty');
```

<hr />

### getPayload

**Description:**

Returns current payload array.

**Parameters:**

- None

**Returns:**

- (array)

**Example:**

```
print_r($jwt->getPayload());
```

<hr />

### setPayload

**Description:**

Set custom value(s) to the current payload array.

**Parameters:**

- `$payload` (array): Key/value pairs to set to the payload array

**Returns:**

- (self)

**Example:**

```
$payload = [
    'user_id' => 10
];

$jwt->setPayload($payload);
```

<hr />

### removePayload

**Description:**

Remove payload key, if existing.

**Parameters:**

- `$key` (string)

**Returns:**

- (self)

**Example:**

```
$jwt->removePayload('user_id');
```

<hr />

### aud

**Description:**

Set audience.

**Parameters:**

- `$aud` (string)

**Returns:**

- (self)

<hr />

### exp

**Description:**

Set expiration time.

**Parameters:**

- `$exp` (int)

**Returns:**

- (self)

<hr />

### iat

**Description:**

Set issued at time.

**Parameters:**

- `$iat` (int)

**Returns:**

- (self)

<hr />

### iss

**Description:**

Set issuer.

**Parameters:**

- `$iss` (string)

**Returns:**

- (self)

<hr />

### jti

**Description:**

Set JWT ID.

**Parameters:**

- `$jti` (string)

**Returns:**

- (self)

<hr />

### nbf

**Description:**

Set not before time.

**Parameters:**

- `$nbf` (int)

**Returns:**

- (self)

<hr />

### sub

**Description:**

Set subject.

**Parameters:**

- `$sub` (string)

**Returns:**

- (self)

<hr />

### encode

**Description:**

Encode and return a signed JWT.

**Parameters:**

- `$payload = []` (array)

**Returns:**

- (string)

**Example:**

```
$now = time();

$token = $jwt->iss('API key whose secret signs the token')
    ->iat($now)    
    ->nbf($now)
    ->exp($now + 86400) // 24 hours
    ->encode([
        'user_id' => 10
    ]);
```

<hr />

### decode

**Description:**

Decode a JWT.

This method validates the token structure as three segments separated by dots.

The returned array will contain the keys `header`, `payload` and `signature`.

If `$validate = true`, the signature and claims will also be validated.

**Parameters:**

- `$jwt` (string): The JWT itself or the entire `Authorization` header can be used
- `$validate = true` (bool): Validate signature and claims

**Returns:**

- (array)

**Throws:**

- `Bayfront\JWT\TokenException`

**Example:**

```
try {

    $decoded = $jwt->decode('encoded.jwt');

} catch (TokenException $e) {
    die($e->getMessage());
}
```

<hr />

### validateSignature

**Description:**

Validate signature.

**Parameters:**

- `$jwt` (string): The JWT itself or the entire `Authorization` header can be used

**Returns:**

- (self)

**Throws:**

- `Bayfront\JWT\TokenException`

**Example:**

```
try {

    $decoded = $jwt->validateSignature('encoded.jwt')->decode('encoded.jwt', false);

} catch (TokenException $e) {
    die($e->getMessage());
}
```

<hr />

### validateClaims

**Description:**

Validate the claims "iat", "nbf" and "exp", if existing.

**Parameters:**

- `$jwt` (string): The JWT itself or the entire `Authorization` header can be used

**Returns:**

- (self)

**Throws:**

- `Bayfront\JWT\TokenException`

**Example:**

```
try {

    $decoded = $jwt->validateClaims('encoded.jwt')->decode('encoded.jwt', false);

} catch (TokenException $e) {
    die($e->getMessage());
}
```

<hr />