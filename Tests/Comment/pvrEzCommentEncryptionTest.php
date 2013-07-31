<?php

namespace pvr\EzCommentBundle\Tests\Comment;

use pvr\EzCommentBundle\Comment\pvrEzCommentEncryption;

class pvrEzCommentEncryptionTest extends \PHPUnit_Framework_TestCase
{
    protected $secret = "toto1234";

    public static function getSecretValues()
    {
        return array(
            array( "toto123", "dG90bzEyMw"),
            array( "starwars", "c3RhcndhcnM" ),
            array( "it's a good day to die", "aXQncyBhIGdvb2QgZGF5IHRvIGRpZQ" )
        );
    }

    /**
     * @dataProvider getSecretValues
     *
     * @cover \pvr\EzCommentBundle\Comment\pvrEzCommentEncryption::safeB64encode
     *
     * @param string $secret
     * @param string $expected
     */
    public function testSafeB64encode( $secret, $expected )
    {
        $encryptService = new pvrEzCommentEncryption( $this->secret );

        $reflector = new \ReflectionClass( 'pvr\\EzCommentBundle\\Comment\\pvrEzCommentEncryption' );
        $method = $reflector->getMethod( 'safeB64encode' );
        $method->setAccessible( true );

        $this->assertEquals(
            $expected,
            $method->invokeArgs( $encryptService, array( $secret ) )
        );
    }


    public static function getInverseSecretValues()
    {
        return array(
            array( "dG90bzEyMw", "toto123" ),
            array( "c3RhcndhcnM", "starwars" ),
            array( "aXQncyBhIGdvb2QgZGF5IHRvIGRpZQ", "it's a good day to die" )
        );
    }

    /**
     * @dataProvider getInverseSecretValues
     *
     * @cover \pvr\EzCommentBundle\Comment\pvrEzCommentEncryption::safeB64decode
     *
     * @param string $crypt
     * @param string $expected
     */
    public function testSafeB64decode( $crypt, $expected )
    {
        $encryptService = new pvrEzCommentEncryption( $this->secret );

        $reflector = new \ReflectionClass( 'pvr\\EzCommentBundle\\Comment\\pvrEzCommentEncryption' );
        $method = $reflector->getMethod( 'safeB64decode' );
        $method->setAccessible( true );

        $this->assertEquals(
            $expected,
            $method->invokeArgs( $encryptService, array( $crypt ) )
        );
    }

    public static function getEncodeValues()
    {
        return array(
            array( "obiwan kenoby", "CGVf5ArbC5CBisFYJAszoEt9mLSdHZz2w6_5tjLJYDc" ),
            array( "plop master", "aoFS0KK-J1WPL7ExUFVlv1mX-1EoZjpZ2C4N0Q9asWo" ),
            array( "azerty1234!#", "2zKxwTEJ2YMWTGJ17kbuatEZqV0O6Nzs3OJH1FNH6f8" )
        );
    }

    /**
     * @dataProvider getEncodeValues
     *
     * @cover \pvr\EzCommentBundle\Comment\pvrEzCommentEncryption::encode
     *
     * @param string $value
     * @param string $expected
     */
    public function testEncode( $value, $expected )
    {
        $encryptService = new pvrEzCommentEncryption( $this->secret );

        $this->assertEquals(
            $expected,
            $encryptService->encode( $value )
        );
    }

    public static function getDecodeValues()
    {
        return array(
            array( "CGVf5ArbC5CBisFYJAszoEt9mLSdHZz2w6_5tjLJYDc", "obiwan kenoby" ),
            array( "aoFS0KK-J1WPL7ExUFVlv1mX-1EoZjpZ2C4N0Q9asWo", "plop master" ),
            array( "2zKxwTEJ2YMWTGJ17kbuatEZqV0O6Nzs3OJH1FNH6f8", "azerty1234!#" )
        );
    }

    /**
     * @dataProvider getDecodeValues
     *
     * @cover \pvr\EzcommentBundle\Comment\pvrEzCommentEncryption::decode
     *
     * @param string $value
     * @param string $expected
     */
    public function testDecode( $value, $expected )
    {
        $encryptService = new pvrEzCommentEncryption( $this->secret );

        $this->assertEquals(
            $expected,
            $encryptService->decode( $value )
        );
    }
}