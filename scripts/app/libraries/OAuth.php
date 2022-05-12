<?php
/**
 * OAuth - One time password generator
 *
 * This class is meant to be compatible with
 * Google Authenticator
 *
 * This class was originally ported from the otphp
 * https://github.com/lelag/otphp
 *
 * @author: me@tes123.id
 */

class OAuth {
    /**
     * The base32 encoded secret key
     * @var string
     */
    public $secret;

    /**
     * The algorithm used for the hmac hash function
     * @var string
     */
    public $digest;

    /**
     * The number of digits in the one-time password
     * @var integer
     */
    public $digits;

/**
     * The interval in seconds for a one-time password timeframe
     * Defaults to 30
     * @var integer
     */
    public $interval;


    /**
     * Constructor for the OTP class
     * @param string $secret the secret key
     * @param array $opt options array can contain the
     * following keys :
     *   @param integer digits : the number of digits in the one time password
     *   Currently Google Authenticator only support 6. Defaults to 6.
     *   @param string digest : the algorithm used for the hmac hash function
     *   Google Authenticator only support sha1. Defaults to sha1
     *
     * @return new OTP class.
     */
    public function __construct($secret, $opt = Array()) {
      $this->digits = isset($opt['digits']) ? $opt['digits'] : 6;
      $this->digest = isset($opt['digest']) ? $opt['digest'] : 'sha1';
      $this->interval = isset($opt['interval']) ? $opt['interval'] : 30;
      $this->secret = $secret;
    }

    /**
     * Generate a one-time password
     *
     * @param integer $input : number used to seed the hmac hash function.
     * This number is usually a counter (HOTP) or calculated based on the current
     * timestamp (see TOTP class).
     * @return integer the one-time password
     */
    public function generateOTP($input) {
      $hash = hash_hmac($this->digest, $this->intToBytestring($input), $this->byteSecret());
      foreach(str_split($hash, 2) as $hex) { // stupid PHP has bin2hex but no hex2bin WTF
        $hmac[] = hexdec($hex);
      }
      $offset = $hmac[19] & 0xf;
      $code = ($hmac[$offset+0] & 0x7F) << 24 |
        ($hmac[$offset + 1] & 0xFF) << 16 |
        ($hmac[$offset + 2] & 0xFF) << 8 |
        ($hmac[$offset + 3] & 0xFF);
      return $code % pow(10, $this->digits);
    }

    /**
     * Returns the binary value of the base32 encoded secret
     * @access private
     * This method should be private but was left public for
     * phpunit tests to work.
     * @return binary secret key
     */
    public function byteSecret() {
      return Base32::decode($this->secret);
    }

    /**
     * Turns an integer in a OATH bytestring
     * @param integer $int
     * @access private
     * @return string bytestring
     */
    public function intToBytestring($int) {
      $result = Array();
      while($int != 0) {
        $result[] = chr($int & 0xFF);
        $int >>= 8;
      }
      return str_pad(join(array_reverse($result)), 8, "\000", STR_PAD_LEFT);
    }

    /**
     *  Get the password for a specific timestamp value
     *
     *  @param integer $timestamp the timestamp which is timecoded and
     *  used to seed the hmac hash function.
     *  @return integer the One Time Password
     */
    public function at($timestamp) {
        return $this->generateOTP($this->timecode($timestamp));
      }

      /**
       *  Get the password for the current timestamp value
       *
       *  @return integer the current One Time Password
       */
      public function now() {
        return $this->generateOTP($this->timecode(time()));
      }

      /**
       * Verify if a password is valid for a specific counter value
       *
       * @param integer $otp the one-time password
       * @param integer $timestamp the timestamp for the a given time, defaults to current time.
       * @return  bool true if the counter is valid, false otherwise
       */
      public function verify($otp, $timestamp = null) {
        if($timestamp === null)
          $timestamp = time();
        return ($otp == $this->at($timestamp));
      }

      /**
       * Returns the uri for a specific secret for totp method.
       * Can be encoded as a image for simple configuration in
       * Google Authenticator.
       *
       * @param string $name the name of the account / profile
       * @param string $issuer the name of authentication issuer
       * @return string the uri for the hmac secret
       */
      public function provisioning_uri($name, $issuer = null) {
        $uri = "otpauth://totp/".urlencode($name)."?secret={$this->secret}";
        if ($issuer) $uri .='&issuer='.urlencode($issuer);

        return $uri;
      }

      /**
       * Transform a timestamp in a counter based on specified internal
       *
       * @param integer $timestamp
       * @return integer the timecode
       */
      protected function timecode($timestamp) {
        return (int)( (((int)$timestamp * 1000) / ($this->interval * 1000)));
      }
}