<?php

/**
 * @package php-jwt
 * @link https://github.com/bayfrontmedia/php-jwt
 * @author John Robinson <john@bayfrontmedia.com>
 * @copyright 2020 Bayfront Media
 */

namespace Bayfront\JWT;

use Exception;

class Jwt
{

    protected $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Sign the data using with the secret using the HS256 algorithm.
     *
     * (Future versions of this library may support additional algorithms)
     *
     * @param string $data
     *
     * @return string
     */

    protected function _sign(string $data): string
    {
        return hash_hmac('sha256', $data, $this->secret, true);
    }

    /**
     * Base64Url encode a string.
     *
     * @param string $string
     *
     * @return string
     */

    protected function _base64UrlEncode(string $string): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }

    /**
     * Base64Url decode a string.
     *
     * @param string $string
     *
     * @return string
     */

    protected function _base64UrlDecode(string $string): string
    {
        return base64_decode(str_replace(['-', '_', ''], ['+', '/', '='], $string));
    }

    /**
     * Create a cryptographically secure secret of random bytes.
     *
     * NOTE: Secrets are meant to be stored, as the same secret used to
     * encode a JWT must be used to validate/decode it.
     *
     * @param int $characters (Number of characters)
     *
     * @return string
     *
     * @throws Exception
     */

    public static function createSecret(int $characters = 32): string
    {
        return bin2hex(random_bytes($characters));
    }

    /*
     * ############################################################
     * Header
     * ############################################################
     */

    protected $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    /**
     * Returns current header array.
     *
     * @return array
     */

    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * Set custom value(s) to the current header array.
     *
     * @param array $header (Key / value pairs to set to the header array)
     *
     * @return self
     */

    public function setHeader(array $header): self
    {

        foreach ($header as $k => $v) {

            $this->header[$k] = $v;

        }

        return $this;

    }

    /**
     * Remove header key, if existing.
     *
     * @param string $key
     *
     * @return self
     */

    public function removeHeader(string $key): self
    {

        if (isset($this->header[$key])) {
            unset($this->header[$key]);
        }

        return $this;

    }

    /*
     * ############################################################
     * Payload
     * ############################################################
     */

    protected $payload = [];

    /**
     * Returns current payload array.
     *
     * @return array
     */

    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * Set custom value(s) to the current payload array.
     *
     * @param array $payload (Key / value pairs to set to the payload array)
     *
     * @return self
     */

    public function setPayload(array $payload): self
    {

        foreach ($payload as $k => $v) {

            $this->payload[$k] = $v;

        }

        return $this;

    }

    /**
     * Remove payload key, if existing.
     *
     * @param string $key
     *
     * @return self
     */

    public function removePayload(string $key): self
    {

        if (isset($this->payload[$key])) {
            unset($this->payload[$key]);
        }

        return $this;

    }

    /**
     * Set audience.
     *
     * @param string $aud
     *
     * @return self
     */

    public function aud(string $aud): self
    {
        $this->payload['aud'] = $aud;
        return $this;
    }

    /**
     * Set expiration time.
     *
     * @param int $exp
     *
     * @return self
     */

    public function exp(int $exp): self
    {
        $this->payload['exp'] = $exp;
        return $this;
    }

    /**
     * Set issued at time.
     *
     * @param int $iat
     *
     * @return self
     */

    public function iat(int $iat): self
    {
        $this->payload['iat'] = $iat;
        return $this;
    }

    /**
     * Set issuer.
     *
     * @param string $iss
     *
     * @return self
     */

    public function iss(string $iss): self
    {
        $this->payload['iss'] = $iss;
        return $this;
    }

    /**
     * Set JWT ID.
     *
     * @param string $jti
     *
     * @return self
     */

    public function jti(string $jti): self
    {
        $this->payload['jti'] = $jti;
        return $this;
    }

    /**
     * Set not before time.
     *
     * @param int $nbf
     *
     * @return self
     */

    public function nbf(int $nbf): self
    {
        $this->payload['nbf'] = $nbf;
        return $this;
    }

    /**
     * Set subject.
     *
     * @param string $sub
     *
     * @return self
     */

    public function sub(string $sub): self
    {
        $this->payload['sub'] = $sub;
        return $this;
    }

    /*
     * ############################################################
     * General
     * ############################################################
     */

    /**
     * Encode and return a signed JWT.
     *
     * @param array $payload
     *
     * @return string
     */

    public function encode(array $payload = []): string
    {

        if (!empty($payload)) {
            $this->setPayload($payload);
        }

        // Set header and payload

        $header = $this->_base64UrlEncode(json_encode($this->header));

        $payload = $this->_base64UrlEncode(json_encode($this->payload));

        // Create encoded signature hash

        $signature = $this->_base64UrlEncode($this->_sign($header . '.' . $payload));

        return $header . '.' . $payload . '.' . $signature; // Return signed JWT

    }

    /**
     * Decode a JWT.
     *
     * This method verifies the token is valid by validating its structure (three segments separated by dots) and
     * signature.
     *
     * The claims "iat", "nbf" and "exp" will be validated, if existing.
     *
     * The returned array will contain the keys "header" and "payload".
     *
     * @param string $jwt
     *
     * @return array
     *
     * @throws TokenException
     */

    public function decode(string $jwt): array
    {

        /*
         * Remove "Bearer " from beginning of string in case
         * the entire Authorization header was used.
         */

        $jwt = explode('.', str_replace('Bearer ', '', $jwt));

        // Validate structure

        if (count($jwt) !== 3) {
            throw new TokenException('Invalid structure');
        }

        // Validate signature

        if ($jwt[2] != $this->_base64UrlEncode($this->_sign($jwt[0] . '.' . $jwt[1]))) {
            throw new TokenException('Invalid signature');
        }

        $payload = json_decode($this->_base64UrlDecode($jwt[1]), true);

        // Validate iat

        if (isset($payload['iat']) && $payload['iat'] > time()) {
            throw new TokenException('Invalid iat claim');
        }

        // Validate nbf

        if (isset($payload['nbf']) && $payload['nbf'] > time()) {
            throw new TokenException('Invalid nbf claim');
        }

        // Validate exp

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new TokenException('Invalid exp claim');
        }

        return [
            'header' => json_decode($this->_base64UrlDecode($jwt[0]), true),
            'payload' => $payload
        ];

    }

}