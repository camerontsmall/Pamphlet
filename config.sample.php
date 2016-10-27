<?php

$config = [
    
    /* General site settings */
    
    "site_title" => "Pamphlet 4",
    "theme_color" => "#2196F3",
    "welcome_message" => "Welcome to the site!",
    
    /* Database settings */
   
    "db_type" => "mongodb",
    /* Connection string for mongodb */
    "mongodb_connect_string" => "mongodb://192.168.1.4:27017/",
    /* db name to use */
    "mongodb_db_name" => "lsutv",
    
    /* User & authentication settings */
    
    /* Enable user and group databases and pages */
    "enable_users" => true,
    /* Enable authentication. enable_users must be true for this to work*/
    "enable_auth" => false,
    /* Additional permissions for use by extenal services (array of strings) */
    "additional_perms" => [],
    
    /* Enter names of Controllers whose collections should be searchable by the Search API */
    "searchable_collections" => ["video","blog","playlist"]
    
];  