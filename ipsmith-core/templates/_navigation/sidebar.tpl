         <div class="well sidebar-nav">
                      <ul class="nav nav-list">
                        <li class="nav-header">Einsatzorte</li>
                        {foreach $globallocations as $location}
                        <li><a href="{$config.baseurl}/list/index.html?locationid={$location.id}">{if $smarty.session.userdata.config.usehumanname eq "1"}{$location.humanname}{else}{$location.locationname}{/if}</a></li>
                        {/foreach}
              </ul><br />
              <ul class="nav nav-list"><li><strong>{lang string="global.admin.name"}</strong></li></ul>
              <ul class="nav nav-list">
                        <li class="nav-header">Benutzer</li>
                        <li><a href="{$config.baseurl}/admin/users/index.html">&Uuml;bersicht</li>
                        <li><a href="{$config.baseurl}/admin/users/edit.html">Erstellen</a></li>
                        <li class="nav-header">{lang string="global.admin.permissions.name"}</li>
                        <li><a href="/admin/permissions/index.html">{lang string="global.admin.overview"}</a></li>
                        <li><a href="/admin/permissions/edit.html">{lang string="global.admin.permissions.create"}</a></li>
                      </ul>
                    </div><!--/.well -->

