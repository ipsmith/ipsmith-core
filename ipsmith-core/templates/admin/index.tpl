<h2>Administration</h2>

<ul class="kacheln">

	{if PermissionManager::CurrentUserHasPermission('can_view_users')}
		<li class="span3" style="text-align:center;padding:10px 10px 0px 0px;margin:0; min-height:60px;vertical-align: middle ;">
	  		<h4><a href="{$config.baseurl}/admin/users/index.html"> Benutzer</a></h4><p>Bearbeiten und erstellen Sie Benutzer</p>
	  	</li>
	{/if}

	{if PermissionManager::CurrentUserHasPermission('can_view_permissions')}
		<li class="span3" style="text-align:center;padding:10px 10px 0px 0px;margin:0; min-height:60px;vertical-align: middle ;">
	  		<h4><a href="{$config.baseurl}/admin/permissions/index.html"> Berechtigungen</a></h4><p>Editieren Sie Berechtigungen und Zugriffsrechte</p>
	  	</li>
	{/if}

	{if PermissionManager::CurrentUserHasPermission('can_view_locations')}
		<li class="span3" style="text-align:center;padding:10px 10px 0px 0px;margin:0; min-height:60px;vertical-align: middle ;">
			<h4><a href="#"> Einsatzorte</a></h4><p>Verwalten Sie Einsatzorte.</p>
		</li>
	{/if}

	{if PermissionManager::CurrentUserHasPermission('can_view_categories')}
		<li class="span3" style="text-align:center;padding:10px 10px 0px 0px;margin:0; min-height:60px;vertical-align: middle ;">
			<h4><a href="#"> Kategorien</a></h4><p>Erstellen, Bearbeiten und Löschen Sie Kategorien</p>
		</li>
	{/if}

	{if PermissionManager::CurrentUserHasPermission('can_view_types')}
		<li class="span3" style="text-align:center;padding:10px 10px 0px 0px;margin:0; min-height:60px;vertical-align: middle ;">
			<h4><a href="{$config.baseurl}/types/index.html"> Typen</a></h4><p>Editieren Sie Typen</p>
		</li>
	{/if}

	{if PermissionManager::CurrentUserHasPermission('can_view_attributes')}
		<li class="span3" style="text-align:center;padding:10px 10px 0px 0px;margin:0; min-height:60px;vertical-align: middle ;">
			<h4><a href="{$config.baseurl}/attributes/index.html"> Attribute</a></h4><p>Erschaffen und Bearbeiten Sie individualisierbare Attribute.</p>
		</li>
	{/if}

	{if PermissionManager::CurrentUserHasPermission('can_view_exporttemplates')}
		<li class="span3" style="text-align:center;padding:10px 10px 0px 0px;margin:0; min-height:60px;vertical-align: middle ;">
			<h4><a href="{$config.baseurl}/exports/index.html"> Exportvorlagen</a></h4><p>Bearbeiten Sie Exportvorlagen.</p>
		</li>
	{/if}

	{if PermissionManager::CurrentUserHasPermission('can_view_audit')}
		<li class="span3" style="text-align:center;padding:10px 10px 0px 0px;margin:0; min-height:60px;vertical-align: middle ;">
			<h4><a href="{$config.baseurl}/admin/audit/index.html"> Audit</a></h4><p>Betrachten Sie das Audit-Protokoll</p>
		</li>
	{/if}

</ul>