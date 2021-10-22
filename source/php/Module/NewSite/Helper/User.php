<?php

namespace Modularity\Module\NewSite\Helper;

class User
{  

  private $email = null; 

  /**
   * Undocumented function
   *
   * @param string $email
   */
  public function __construct($email) {
    if($this->isValidEmail($email)) {
      $this->email = $email; 
    } else {
      return new WP_Error(
        'invalid_email', 
        __("The email adress provided is not consiedered as valid.", 'modularity')
      ); 
    }
  }

  /**
   * Undocumented function
   *
   * @param string $email
   * @return boolean
   */
  private function isValidEmail($email) : bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL); 
  }
  
  /**
   * Undocumented function
   *
   * @return void
   */
  public function getUser() {
    
    if(!$user = get_user_by('email', $this->email)) {
      return wp_create_user(
        $this->emailToUsername($this->email), 
        $this->randomPassword(), 
        $this->email
      );
    }

    if(is_a($user, 'WP_User') && isset($user->data->ID)) {
      return $user->data->ID; 
    } else {
      return new WP_Error(
        'invalid_user', 
        __("Could not create or find a matching user.", 'modularity')
      ); 
    }

  }

  /**
   * Undocumented function
   *
   * @return string
   */
  private function randomPassword() : string {
    return wp_generate_password(); 
  }

  /**
   * Undocumented function
   *
   * @param [type] $email
   * @return string
   */
  private function emailToUsername($email) : string {
    return sanitize_user($email); 
  }
}