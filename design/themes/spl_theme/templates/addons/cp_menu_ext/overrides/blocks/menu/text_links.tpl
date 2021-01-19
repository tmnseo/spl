{** block-description:text_links **}

{if $block.properties.show_items_in_line == 'Y'}
    {assign var="inline" value=true}
{/if}

{assign var="text_links_id" value=$block.snapping_id}

{if $items}
    {if $inline && !$submenu}
    <div class="ty-text-links-wrapper">
        <span id="sw_text_links_{$text_links_id}" class="ty-text-links-btn cm-combination visible-phone">
            <i class="ty-icon-short-list"></i>
            <i class="ty-icon-down-micro ty-text-links-btn__arrow"></i>
        </span>
    {/if}
        <ul {if !$submenu}id="text_links_{$text_links_id}"{/if} class="ty-text-links{if $inline && !$submenu} cm-popup-box ty-text-links_show_inline{/if}">
            {foreach from=$items item="menu"}
                {if $menu.cp_is_popup_page == 'Y' && $menu.href}
                    {$menu.href = $menu.href|cat:"?cp_prev_url=`$config.current_url|escape:url`"}
                {/if} 
                <li class="ty-text-links__item ty-level-{$menu.level|default:0}{if $menu.active} ty-text-links__active{/if}{if $menu.class} {$menu.class}{/if}{if $inline && !$submenu && $menu.subitems} ty-text-links__subitems{/if}">
                    <a class="ty-text-links__a{if $menu.cp_is_popup_page == 'Y'} cm-dialog-opener cm-dialog-auto-size{/if}"
                    {if $menu.href} href="{$menu.href|fn_url}"{/if} 
                    {if $menu.cp_nofollow == 'Y'} rel="nofollow"{/if}
                    {if $menu.cp_is_popup_page == 'Y'} data-ca-target-id="cp_popup_page_{$menu.param_id}"  
                        {if $menu.class} data-ca-dialog-class="{$menu.class}_popup"{/if}
                    {/if}
                    {if $menu.cp_open_in_new_window == "Y"} target="_blank"{/if}
                    >
                        {$menu.item}
                    </a> 
                    {if $menu.subitems}
                        {include file="blocks/menu/text_links.tpl" items=$menu.subitems submenu=true}
                    {/if}
                    {*CP: cp_popup_notifications*} 
                    {if $menu.cp_is_popup_page == 'Y'} 
                        <div id="p_popup_page_{$menu.param_id}" class="hidden"  title="{__("cp_popup_notifications.feedback")}"></div> 
                    {/if}
                    {*CP: cp_popup_notifications*}
                </li>
                
            {/foreach}
        </ul>

    {if $inline && !$submenu}
    </div>
    {/if}
{/if}