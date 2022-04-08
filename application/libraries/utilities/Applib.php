<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Applib
{
  private static $ci;

  public function __construct()
  {
    self::$ci =& get_instance();
  }

  public static function getPastDate($numberOfPastWeeks)
  {
    $todaysDate = new DateTime(date("Y-m-d 00:00:00"));
    $weekdayToday = $todaysDate->format("w");

    $daysToSubtract = $weekdayToday + (7 * $numberOfPastWeeks);

    return $todaysDate->sub(new DateInterval("P" . $daysToSubtract . "D"))->format("Y-m-d H:i:s");
  }

  public static function createOTP()
  {
    $otp = rand(10000, 99999);
    return $otp;
  }

  public static function createDefaultPassword(){
    $pwd = rand(10000000, 99999999);
    return $pwd;
  }

  public static function hashPassword($pwd)
  {
    return password_hash($pwd, PASSWORD_DEFAULT);
  }

  public static function isPasswordCorrect($pwd, $hash)
  {
    log_message('info',print_r([$pwd, $hash, password_verify($pwd, $hash)],true));
    return password_verify($pwd, $hash) || $hash == $pwd;
  }

  public static function formatJSONOutput($status, Array $out)
  {
    if (! is_bool($status)) throw new Exception("The status must be a boolean value: true or false.");
    $output = array(
      "status" => ($status)? "succeeded" : "failed",
      "data" => $out
    );
    $json = json_encode($output);
    log_message("info", $json);
    return $json;
  }

  public static function validateEmail($email)
  { 
    //return preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/', $phoneNo);
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  public static function cleanPhoneNumber($phoneNo)
  {
    $phone = ltrim($phoneNo, '+');
    $phone = ltrim($phone, '234');
    $phone = ltrim($phone, '0');
    $newPhoneNumberFormat = '234' . $phone;
    return $newPhoneNumberFormat;
  }

  public static function generateToken($tokenLen=32)
  {
    return bin2hex(random_bytes($tokenLen));
  }

}
