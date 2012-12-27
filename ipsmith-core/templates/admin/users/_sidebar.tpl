{if PermissionManager::CurrentUserHasPermission('can_view_users')}
<li>
  <a href="{$config.baseurl}/admin/users/index.html"><i class="icon-chevron-right"></i> Benutzer</a>
</li>
{/if}
{if PermissionManager::CurrentUserHasPermission('can_create_users')}
<li>
  <a href="{$config.baseurl}/admin/users/edit.html"><i class="icon-chevron-right"></i> Erstellen</a>
</li>
{/if}
