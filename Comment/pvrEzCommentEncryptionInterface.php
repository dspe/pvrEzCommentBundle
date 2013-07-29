<?php

namespace pvr\EzCommentBundle\Comment;

Interface pvrEzCommentEncryptionInterface
{

    public function __construct( $secret );

    /**
     * @param $value        string to encode
     * @return bool|string  return crypt code or false
     */
    public function encode( $value );

    /**
     * @param $value        string to decode
     * @return bool|string  return decrypted code or false
     */
    public function decode( $value );

}
