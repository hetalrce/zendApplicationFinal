<?php

namespace User\Service;

use Zend\Crypt\Password\Bcrypt;

class UserEncryption {

    private $_encryptionMethod = "AES-256-CBC";
    private $_secretHash = "25c6c7jn35b9979bc51f2136cd14r0ff";
    private $_viCode = "hjdikngd@kn!Dfgh";

    /**
     * encryptUrlParameter
     *
     * @param string $string            
     * @return string
     */
    public function encryptUrlParameter($string = "") {
        $encryptedString = openssl_encrypt($string, $this->_encryptionMethod, $this->_secretHash, false, $this->_viCode);
        $encryptedString = base64_encode($encryptedString);
        return $encryptedString;
    }

    /**
     * decryptUrlParameter
     *
     * @param string $string            
     * @return string
     */
    public function decryptUrlParameter($string = "") {
        $decryptedString = base64_decode($string);
        $decryptedString = openssl_decrypt($decryptedString, $this->_encryptionMethod, $this->_secretHash, false, $this->_viCode);
        return $decryptedString;
    }

}
