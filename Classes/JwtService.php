<?php
namespace Wazisera\Utility\JsonWebToken;

/*                                                                        *
 * This script belongs to the package "Wazisera.Utility.JsonWebToken".    *
 *                                                                        *
 *                                                                        */

use Firebase\JWT\JWT;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class JwtService {

    /**
     * @Flow\InjectConfiguration(path="algorithms")
     * @var array
     */
    protected $algorithms = array();

    /**
     * @Flow\InjectConfiguration(path="tokenSources")
     * @var array
     */
    protected $tokenSources = array();

    /**
     * @var KeyProvider
     * @Flow\Inject
     */
    protected $keyProvider;

    /**
     * @param array $payload
     * @return string
     */
    public function createJsonWebToken($payload = array()) {
        return JWT::encode($payload, $this->keyProvider->getPublicKey(), $this->algorithms[0]);
    }

    /**
     * @param string $encodedJWT
     * @return object
     */
    public function decodeJsonWebToken($encodedJWT) {
        return JWT::decode($encodedJWT, $this->keyProvider->getPublicKey(), $this->algorithms);
    }

    /**
     * Returns the cookiename for the token.
     *
     * @return string
     */
    public function getCookieName() {
        return $this->getName('cookie');
    }


    /**
     * Returns the header name from the configuration.
     *
     * @return string
     */
    public function getHeaderName() {
        return $this->getName('header');
    }

    /**
     * Returns the query name from the configuration.
     *
     * @return string
     */
    public function getQueryName() {
        return $this->getName('query');
    }

    /**
     * @param string $from
     * @return string
     */
    protected function getName($from) {
        foreach ($this->tokenSources as $tokenSource) {
            $name = $tokenSource['name'];
            if ($tokenSource['from'] == $from) {
                return $name;
                break;
            }
        }
        return '';
    }

}
?>