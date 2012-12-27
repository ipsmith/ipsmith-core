<div class="span2 ipsmith-sidebar">
  <ul class="nav nav-list ipsmith-sidenav" data-spy="affix" data-offset-top="60">
    {if in_array($currentModule,array('list','item'))}
      {include file="list/_sidebar.tpl"}
    {/if}

    {if in_array($currentModule,array('about','types','admin/users'))}
      {include file="$currentModule/_sidebar.tpl"}
    {/if}
  </ul>
</div>