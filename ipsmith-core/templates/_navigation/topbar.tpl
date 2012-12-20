    <div class="navbar navbar-inverse navbar-fixed-top">
           <div class="navbar-inner">
             <div class="container-fluid">
               <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </a>
               <a class="brand" href="#">IPSmith</a>
              <div class="nav-collapse collapse">
                 <p class="navbar-text pull-right">
                   {if $smarty.session.userdata.id eq 0}
                   <a href="{$config.baseurl}/user/login.html">Bitte melden Sie sich an.</a>
                   {else}

                   Angemeldet als <a href="#" class="navbar-link">{if isset($smarty.session.userdata.firstname) && isset($smarty.session.userdata.lastname)}{$smarty.session.userdata.firstname} {$smarty.session.userdata.lastname}{else}{$smarty.session.userdata.username}{/if}</a>. <a href="{$config.baseurl}/user/logout.html">Abmelden</a>

                   {/if}
                 </p>
                 <ul class="nav">
                   {if PermissionManager::CurrentUserHasPermission('can_view_ips')}<li{if $currentModule=='list'} class="active"{/if}><a href="{$config.baseurl}/list/index.html">Adressen</a></li>{/if}
                   {if PermissionManager::CurrentUserHasPermission('can_view_users')}<li><a href="#">Benutzer</a></li>{/if}
                   {if PermissionManager::CurrentUserHasPermission('can_view_locations')}<li><a href="#">Einsatzorte</a></li>{/if}
                   {if PermissionManager::CurrentUserHasPermission('can_view_categories')}<li><a href="#">Kategorien</a></li>{/if}
                   {if PermissionManager::CurrentUserHasPermission('can_view_types')}<li><a href="{$config.baseurl}/types/index.html">Typen</a></li>{/if}
                   {if PermissionManager::CurrentUserHasPermission('can_view_attributes')}<li><a href="{$config.baseurl}/attributes/list.html">Attribute</a></li>{/if}
                   {if PermissionManager::CurrentUserHasPermission('can_view_admin')}<li><a href="#">Einstellungen</a></li>{/if}
                   {if PermissionManager::CurrentUserHasPermission('can_view_about')}<li{if $currentModule=='about'} class="active"{/if}><a href="{$config.baseurl}/about/info.html">About</a></li>{/if}
                 </ul>
               </div><!--/.nav-collapse -->
             </div>
           </div>
         </div>

