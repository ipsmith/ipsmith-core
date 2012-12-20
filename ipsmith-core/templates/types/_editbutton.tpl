{if PermissionManager::CurrentUserHasPermission('can_edit_types') ||  PermissionManager::CurrentUserHasPermission('can_delete_types')}
<div class="btn-group">
	<a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#">
		Bearbeiten
		<span class="caret"></span>
	</a>

	<ul class="dropdown-menu  pull-right">
          {if PermissionManager::CurrentUserHasPermission('can_edit_types')}
			<li>
				<a href="{$config.baseurl}/types/edit.html?id={$myentry.id}"><i class="icon-edit"></i> Editieren</a>
			</li>
		{/if}

          {if PermissionManager::CurrentUserHasPermission('can_delete_types')}
			<li>
				<a href="{$config.baseurl}/types/delete.html?entryid={$myentry.id}"><i class="icon-remove-circle"></i> Löschen</a>
			</li>
		{/if}
  </ul>
</div>
{/if}