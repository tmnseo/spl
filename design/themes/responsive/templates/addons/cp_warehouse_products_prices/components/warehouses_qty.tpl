{$show_qty = true}
{$cart_button_exists = true}

{if ($settings.General.inventory_tracking == "Y" && $settings.General.allow_negative_amount != "Y" && (($warehouse_data.amount <= 0 || $warehouse_data.amount < $product.min_qty) && $product.tracking != "ProductTracking::DO_NOT_TRACK"|enum) && $product.is_edp != "Y")}
    {$show_qty = false}
    {$cart_button_exists = false}
{/if}
<div class="cm-reload-{$obj_prefix}{$obj_id}" id="qty_update_{$obj_prefix}{$obj_id}">
<input type="hidden" name="appearance[show_qty]" value="{$show_qty}" />
<input type="hidden" name="appearance[capture_options_vs_qty]" value="{$capture_options_vs_qty}" />
{if !empty($product.selected_amount)}
    {assign var="default_amount" value=$product.selected_amount}
{elseif !empty($product.min_qty)}
    {assign var="default_amount" value=$product.min_qty}
{elseif !empty($product.qty_step)}
    {assign var="default_amount" value=$product.qty_step}
{else}
    {assign var="default_amount" value="1"}
{/if}

{if $show_qty && $product.is_edp !== "Y" && $cart_button_exists == true && ($settings.Checkout.allow_anonymous_shopping == "allow_shopping" || $auth.user_id) && $product.avail_since <= $smarty.const.TIME || ($product.avail_since > $smarty.const.TIME && $product.out_of_stock_actions == "OutOfStockActions::BUY_IN_ADVANCE"|enum)}
    <div class="ty-qty clearfix{if $settings.Appearance.quantity_changer == "Y"} changer{/if}" id="qty_{$obj_prefix}{$obj_id}">
        {if !$hide_qty_label}<label class="ty-control-group__label" for="qty_count_{$obj_prefix}{$obj_id}">{$quantity_text|default:__("quantity")}:</label>{/if}
        {if $product.qty_content}
        <select name="product_data[{$obj_id}][amount]" id="qty_count_{$obj_prefix}{$obj_id}">
        {assign var="a_name" value="product_amount_`$obj_prefix``$obj_id`"}
        {assign var="selected_amount" value=false}
        {foreach name="`$a_name`" from=$product.qty_content item="var"}
            <option value="{$var}" {if $product.selected_amount && ($product.selected_amount == $var || ($smarty.foreach.$a_name.last && !$selected_amount))}{assign var="selected_amount" value=true}selected="selected"{/if}>{$var}</option>
        {/foreach}
        </select>
        {else}
        <div class="ty-center ty-value-changer cm-value-changer">
            {if $settings.Appearance.quantity_changer == "Y"}
                <a class="cm-increase ty-value-changer__increase">&#43;</a>
            {/if}
            <input {if $product.qty_step > 1}readonly="readonly"{/if} type="text" size="5" class="ty-value-changer__input cm-amount" id="qty_count_{$obj_prefix}{$obj_id}_{$store.store_location_id}" name="product_data[{$obj_id}][amount]" value="{$default_amount}"{if $product.qty_step > 1} data-ca-step="{$product.qty_step}"{/if} data-ca-min-qty="{if $product.min_qty > 1}{$product.min_qty}{else}1{/if}" />
            {if $settings.Appearance.quantity_changer == "Y"}
                <a class="cm-decrease ty-value-changer__decrease">&minus;</a>
            {/if}
        </div>
        {/if}
    </div>
    {if $product.prices}
        {include file="views/products/components/products_qty_discounts.tpl"}
    {/if}
{elseif !$bulk_add}
    <input type="hidden" name="product_data[{$obj_id}][amount]" value="{$default_amount}" />
{/if}
<!--qty_update_{$obj_prefix}{$obj_id}--></div>
