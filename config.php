<?php
  $config = array(
    // Title of the site
    "Site_Title" => "uWrite",

    // Author and description of the site, which are meta tags in head
    "Site_Author" => "SGL",
    "Site_Description" => "uWrite is a minimalist WEB-3 publishing tool that allows you to create richly formatted posts",

    // Language of the site || en: English, hu: Hungarian
    "Site_Language" => "en",

    // Is the site offline? If true, a message will appear on the homepage
    "Is_Offline" => false,

    // MySQL login details
    "MySQL_host" => "localhost",
    "MySQL_user" => "root",
    "MySQL_pass" => "",
    "MySQL_db" => "uwrite",

    // Is debug enabled?
    "Debug" => false,
    // Current timezone
    "Timezone" => "Europe/Moscow",
    // Date show type on view page
    "DateShowTypeView" => "F j, Y"

  );
  date_default_timezone_set($config['Timezone']);
  require("lang/" . $config["Site_Language"] . ".php");
  if($config["Is_Offline"]){
    include("offline.php"); exit;
  }
