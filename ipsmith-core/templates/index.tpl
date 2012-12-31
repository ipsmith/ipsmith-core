<!DOCTYPE html>
<html lang="{$smarty.session.userdata.language}">
<!--                                                                            -->
<!-- IPSmith: Copyright 2012, IPSmith Project. This Software is under the AGPL. -->
<!--          Web: http://ipsmith.org/ - Lists: http://ipsmith.org/lists        -->
<!--   GNU AFFERO General Public License: http://www.gnu.org/licenses/agpl.txt  -->
<!--                                                                            -->
  <head>
    <meta charset="utf-8">
    <title>{$currentTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="{$config.baseurl}/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{$config.baseurl}/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="{$config.baseurl}/assets/colorpicker/css/colorpicker.css" rel="stylesheet">
    <link href="{$config.baseurl}/assets/css/ipsmith.css" rel="stylesheet">
    <link href="{$config.baseurl}/assets/css/dd.css" rel="stylesheet">
    <script src="{$config.baseurl}/assets/js/jquery-1.8.2.min.js"></script>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    {include file="_navigation/topbar.tpl"}

    <div class="container-fluid">
      <div class="row-fluid">
        {if PermissionManager::CurrentUserHasRole('user') && ($currentModule!='error' && $currentPage!='catch')}
            {include file="_navigation/sidebar.tpl"}
        {/if}
        <div class="span{if PermissionManager::CurrentUserHasRole('user')  && ($currentModule!='error' && $currentPage!='catch')}9{else}12{/if}">
          {include file="currentmessages.tpl"}
		      {include file="$currentModule/$currentPage.tpl"}
	      </div>
      </div>
    </div>

    <footer class="footer">
      <div class="container">
        <p>&copy; 2012 <a href="http://ipsmith.org" target="_blank">IPSmith Project</a> and <a href="https://twitter.com/mrbendig" target="_blank">@mrbendig</a>.</p>
        <p>Code licensed under <a href="{$config.baseurl}/about/license.html">GNU LESSER GENERAL PUBLIC LICENSE 3</a>.</p>
        <p><a href="http://glyphicons.com">Glyphicons Free</a> licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
        <ul class="footer-links">
          <li><a href="http://blog.ipsmith.org">Blog</a></li>
          <li class="muted">&middot;</li>
          <li><a href="http://bugs.ipsmith.org">Issues</a></li>
                  </ul>
      </div>
    </footer>

    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-transition.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-alert.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-modal.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-dropdown.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-scrollspy.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-tab.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-tooltip.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-popover.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-button.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-collapse.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-carousel.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-typeahead.js"></script>
    <script src="{$config.baseurl}/assets/bootstrap/js/bootstrap-affix.js"></script>
    <script src="{$config.baseurl}/assets/js/ipsmith.js"></script>

  </body>
</html>