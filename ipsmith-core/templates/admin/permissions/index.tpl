<table class="table table-striped ipsmith">
	{foreach $permissions as $permission name='permissionsForeach'}

	{if ($smarty.foreach.permissionsForeach.iteration is div by
		$smarty.session.userdata.config.repeatheaders) ||
		($smarty.foreach.permissionsForeach.first)}

	{if $smarty.foreach.permissionsForeach.first}
		<thead>
	{/if}

 		<tr>
			<td>&nbsp;</td>
			{foreach $roles as $role}
				<td><strong>{$role.name}</strong></td>
			{/foreach}
		</tr>

	{if $smarty.foreach.permissionsForeach.first}
		</thead>
	{/if}

	{/if}

		<tr>
			<td class="span3"><strong>{$permission.name}</strong></td>

			{foreach $roles as $role}
				<td>
					<a href="{$config.baseurl}/{$currentModule}/changepermission.html?role={$role.id}&amp;permission={$permission.id}">
						{if isset($assigned[$role.name][$permission.name])}
							<img class="icon-ok"></i>
						{else}
							<img class="icon-remove"></i>
						{/if}
					</a>
				</td>
			{/foreach}

		</tr>
	{/foreach}
</table>