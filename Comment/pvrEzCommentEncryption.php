<?php

/*
 * This file is part of the pvrEzComment package.
 *
 * (c) Philippe Vincent-Royol <vincent.royol@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace pvr\EzCommentBundle\Comment;

use pvr\EzCommentBundle\Comment\PvrEzCommentEncryptionInterface;

class PvrEzCommentEncryption implements pvrEzCommentEncryptionInterface
{
    /**
     * @var Contains the secret key
     */
    protected $secretKey;

    /**
     * @param $secret
     */
    public function __construct( $secret )
    {
        $this->secretKey = substr( $secret, 0, 32 );
    }

    /**
     * @param $string
     * @return mixed
     */
    protected function safeB64encode( $string )
    {
        $data = base64_encode( $string );
        $data = str_replace( array( '+', '/', '=' ), array( '-', '_', '' ), $data );
        return $data;
    }

    /**
     * @param $string
     * @return string
     */
    protected function safeB64decode( $string )
    {
        $data = str_replace( array( '-', '_' ), array( '+', '/' ), $string );
        $mod4 = strlen( $data ) % 4;
        if ( $mod4 )
        {
            $data .= substr( '====', $mod4 );
        }
        return base64_decode( $data );
    }

    /**
     * @param $value        string to encode
     * @return bool|string  return crypt code or false
     */
    public function encode( $value )
    {
        if ( !$value )
        {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
        $iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
        $cryptText = mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $this->secretKey, $text, MCRYPT_MODE_ECB, $iv );

        return trim( $this->safeB64encode( $cryptText ) );
    }

    /**
     * @param $value        string to decode
     * @return bool|string  return decrypted code or false
     */
    public function decode( $value )
    {
        if ( !$value )
        {
            return false;
        }
        $cryptText = $this->safeB64decode( $value );
        $iv_size = mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
        $iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
        $decryptText = mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $this->secretKey, $cryptText, MCRYPT_MODE_ECB, $iv );

        return trim( $decryptText );
    }
}
