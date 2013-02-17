{if PermissionManager::CurrentUserHasPermission('can_edit_edit') ||  PermissionManager::CurrentUserHasPermission('can_delete_entries')}
	<div class="btn-group">
		<a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#">
			Bearbeiten
			<span class="caret"></span>
		</a>

		<ul class="dropdown-menu  pull-right">
			{if PermissionManager::CurrentUserHasPermission('can_edit_entries')}
				<li>
					<a href="{$config.baseurl}/item/edithost.html?id={$entry->id}"><i class="icon-edit"></i> Editieren</a>
				</li>
			{/if}

			{if PermissionManager::CurrentUserHasPermission('can_delete_entries')}
				<li>
					<a href="{$config.baseurl}/list/delete.html?entryid={$entry->id}"><i class="icon-remove-circle"></i> Löschen</a>
				</li>
			{/if}
		</ul>
	</div>
{/if}