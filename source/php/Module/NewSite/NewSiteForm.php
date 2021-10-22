<?php

namespace Modularity\Module\NewSite;

use Modularity\Module\NewSite\Helper\User as UserHelper; 

class NewSiteForm
{
  public function __construct() {
    $this->handleSubmit(); 
  }

  public function handleSubmit() {

    //Use as object
    $post = $this->sanitizePostData((object) $_POST);

    //Create a new or fetch a existing user
    $user = $this->fetchCreateUser($post->email); 

    //Create a new site, with a user. 
    if($user && is_numeric($user)) {
      $x = $this->createNewSite(
        (int)   $user, 
        (array) $post
      ); 

      var_dump($x);

      return $x;



    } elseif(is_wp_error($user)) {
      return $user; 
    }

    return false;
  }

  /**
   * Undocumented function
   *
   * @param [type] $email
   * @return void
   */
  public function fetchCreateUser($email) {
    $userhelper = new UserHelper($email); 
    return $userhelper->getUser(); 
  }

  /**
   * Undocumented function
   *
   * @return void
   */
  public function createNewSite($userID, $args = array()) {

    //Subdomain install
    if(defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL === true) {
      return wpmu_create_blog(
        $args['slug'] . "." . parse_url(network_home_url(), PHP_URL_HOST), 
        '/', 
        $args['name'], 
        $userId
      ); 
    }

    //Subfolder install
    return wpmu_create_blog(
      parse_url(network_home_url(), PHP_URL_HOST), 
      $args['slug']."/", 
      $args['name'], 
      $userId
    );
  }

  /**
   * Validate post data
   *
   * @return void
   */
  private function sanitizePostData($post) {
    return (object) [
      'email'   => filter_var($post->useremail, FILTER_SANITIZE_EMAIL),
      'slug'    => sanitize_title($post->siteslug),
      'title'   => sanitize_title($post->sitename),
    ];
  }

}