<?php
$defaultconfig = array();

$defaultconfig["appidentifier"] = "ipsmith-dev";

$defaultconfig["baseurl"] = "http://localhost/~rbendig/ipsmithdev";
$defaultconfig["baselanguage"] = 'en';

$defaultconfig["db"] = array();
$defaultconfig["db"]["host"] = "127.0.0.1";
$defaultconfig["db"]["user"] = "root";
$defaultconfig["db"]["pass"] = "";
$defaultconfig["db"]["name"] = "ipsmith";
$defaultconfig["db"]["driver"] = "pdo_mysql";

$defaultconfig["template"]["debugging"] = false;
$defaultconfig["template"]["caching"] = false;
$defaultconfig["template"]["cache_lifetime"] = 1;
$defaultconfig["template"]["force_compile"] = true;
$defaultconfig["template"]["use_sub_dirs"] = true;

$defaultconfig["defaultsettings"] = array();
$defaultconfig["defaultsettings"]["usehumanname"] = 0;
$defaultconfig["defaultsettings"]["displayids"] = 0;
