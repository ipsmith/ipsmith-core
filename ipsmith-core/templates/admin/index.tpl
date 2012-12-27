<h1>Administration</h1>
<div class="row-fluid">
	{if PermissionManager::CurrentUserHasPermission('can_view_users')}
		<div class="span4">
	  		<h4><a href="{$config.baseurl}/admin/users/index.html"><i class="icon-user"></i> Benutzer</a></h4><p>Bearbeiten und erstellen Sie Benutzer</p>
	  	</div>
	{/if}
	{if PermissionManager::CurrentUserHasPermission('can_view_permissions')}
		<div class="span4">
	  		<h4><a href="{$config.baseurl}/admin/permissions/index.html"><i class="icon-check"></i> Berechtigungen</a></h4><p>Editieren Sie Berechtigungen und Zugriffsrechte</p>
	  	</div>
	{/if}
</div>