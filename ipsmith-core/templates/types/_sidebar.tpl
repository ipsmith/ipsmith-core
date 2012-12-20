{if PermissionManager::CurrentUserHasPermission('can_view_types')}
<li>
  <a href="{$config.baseurl}/types/index.html"><i class="icon-chevron-right"></i> Übersicht</a>
</li>
{/if}
{if PermissionManager::CurrentUserHasPermission('can_create_types')}
<li>
  <a href="{$config.baseurl}/types/edit.html"><i class="icon-chevron-right"></i> Erstellen</a>
</li>
{/if}
