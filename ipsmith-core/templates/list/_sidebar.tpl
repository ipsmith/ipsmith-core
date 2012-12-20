      {foreach $globallocations as $location}
              <li>
                        <a href="{$config.baseurl}/list/index.html?locationid={$location.id}"><i class="icon-chevron-right"></i>
                                    {if $smarty.session.userdata.config.usehumanname eq "1"}
                                                  {$location.humanname}
                                                              {else}
                                                                            {$location.locationname}
                                                                                        {/if}
                                                                                                   </a>
                                                                                                            </li>
                                                                                                                    {/foreach}

