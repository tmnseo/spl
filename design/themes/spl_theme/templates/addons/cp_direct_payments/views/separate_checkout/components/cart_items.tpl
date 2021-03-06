{capture name="cartbox"}
{if $runtime.mode == "checkout"}
    {if $cart.coupons|floatval}<input type="hidden" name="c_id" value="" />{/if}
    {hook name="checkout:form_data"}
    {/hook}
{/if}



    {*cart-power additional info*}
    <div class="cp-cart-vendor__header clearfix">
        <div class="cp-cart-vendor__header_left cm-combination open" id="sw_cart_items{$suffix_key}">
            <div class="cp-cart-vendor__info">
                <div class="cp-cart-vendor__vendor">
                    {__("vendor")}: <strong>{$vendor}</strong>
                </div>
                <div class="cp-cart-vendor__warehouse">
                    {__("cp_checkout_modifications_warehouse")}: <strong>{if $location_warehouse.city}{$location_warehouse.city}{/if}{if $location_warehouse.pickup_address}, {$location_warehouse.pickup_address}{/if}</strong>
                </div>
            </div>
            <div class="cp-cart-vendor__total-price"
                 id="checkout_totals_header{$suffix_key}">
                {* {__("preliminary_cost")}: *}
                {include file="common/price.tpl" value=$cart.display_subtotal}
                {if $cart.taxes && $settings.Appearance.cart_prices_w_taxes == "Y"}<span class="ty-list__clean">{__("cp_spl_theme.including_tax")}</span>{/if}
            <!--checkout_totals_header{$suffix_key}--></div>
            <span class="cp-cart-vendor__title-toggle">
                <i class="ty-icon-down-open"></i>
                <i class="ty-icon-up-open"></i>
            </span>
        </div>
        <div class="cp-cart-vendor__header_right">
             {include file="buttons/button.tpl"
                         but_id=$but_id
                         but_text=__("recalculate")
                         but_meta="ty-btn--recalculate-cart cm-ajax-send-form cm-post cm-ajax cm-ajax-full-render hidden hidden-phone hidden-tablet"
                         but_name="dispatch[checkout.update]"
                         but_target_form="cart_form{$suffix_key}"
                         but_role="text"
            }
            {if count($carts) > 1} 
                {$btn_vendor_checkout = __("place_in_one_order")}
            {else}
                {$btn_vendor_checkout = __("checkout")}
            {/if}
            {if $payment_methods}
                {$m_name="checkout"}
                {$link_href="checkout.checkout&vendor_id=`$vendor_id`"}
                {include file="buttons/proceed_to_checkout.tpl"
                    but_href=$link_href
                    but_meta="ty-btn__secondary ty-btn__vendor-checkout" 
                    but_text=$btn_vendor_checkout
                }
            {/if}
            {include file="buttons/button.tpl" 
                    but_text=__("cp_direct_payments.clear_vendor") 
                    but_href="checkout.clear&vendor_id=`$vendor_id`" 
                    but_meta="cm-confirm ty-cart-content__clear-button ty-cart-content__clear-button_vendor" 
                    but_title=__("cp_direct_payments.clear_vendor")
            }
        </div>
        
    </div>
    {* {include file="views/companies/components/product_company_data.tpl" company_name=$vendor company_id=$vendor_id} *}

    {* {$location_warehouse.name} || {$location_warehouse.city} || {$location_warehouse.pickup_address} || {$location_warehouse.country_title} *}



    <div class="cp-cart-vendor__body" id="cart_items{$suffix_key}">
    <table class="ty-cart-content ty-table" style="width: 100%">

    {assign var="prods" value=false}

    <thead>
        <tr>
            <th class="ty-cart-content__title product" style="width: 400px"><span>{__("product")}</span></th>
            <th class="ty-cart-content__title manufacturer" style="width: 140px" ><span>{__("cp_checkout_modifications_manufacturer")}</span></th>
            <th class="ty-cart-content__title manufacturer_code" style="width: 120px"><span>{__("cp_ls_product_code")}</span></th>
            <th class="ty-cart-content__title unit_price" style="width: 190px"><span>{__("unit_price")}</span></th>
            <th class="ty-cart-content__title quantity-cell" style="width: 70px"><span>{__("quantity")}</span></th>
            <th class="ty-cart-content__title total-price" style="width: 120px"><span>{__("total_price")}</span></th>
            <th class="ty-cart-content__title ty-left" style="width: 160px"><span>&nbsp;</span></th>
        </tr>
    </thead>

    <tbody>

    {if $cart_products}
        {foreach from=$cart_products item="product" key="key" name="cart_products"}
            {assign var="obj_id" value=$product.object_id|default:$key}
            {hook name="checkout:items_list"}

                {if !$cart.products.$key.extra.parent}
                    <tr>
                        {* <td class="ty-cart-content__product-elem ty-cart-content__image-block">
                            {if $runtime.mode == "cart" || $show_images}
                                <div class="ty-cart-content__image cm-reload-{$obj_id}" id="product_image_update_{$obj_id}">
                                    {hook name="checkout:product_icon"}
                                        <a href="{"products.view?product_id=`$product.product_id`"|fn_url}">
                                        {include file="common/image.tpl" obj_id=$key images=$product.main_pair image_width=$settings.Thumbnails.product_cart_thumbnail_width image_height=$settings.Thumbnails.product_cart_thumbnail_height}</a>
                                    {/hook}
                                <!--product_image_update_{$obj_id}--></div>
                            {/if}
                        </td> *}

                        <td class="ty-cart-content__product-elem ty-cart-content__description">
                            {strip}
                                <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="ty-cart-content__product-title">
                                    {$product.product nofilter}
                                </a>
                            {/strip}
                            {hook name="products:product_additional_info"}
                                {* <div class="ty-cart-content__sku ty-sku cm-hidden-wrapper{if !$product.product_code} hidden{/if}" id="sku_{$key}">
                                    {__("sku")}: <span class="cm-reload-{$obj_id}" id="product_code_update_{$obj_id}">{$product.product_code}<!--product_code_update_{$obj_id}--></span>
                                </div> *}
                                {* <div class="ty-cart-content__sku ty-sku cp-visible-tablet {if !$product.manufacturer} hidden{/if}">
                                    {__("cp_checkout_modifications_manufacturer")}: <span>{$product.manufacturer}</span>
                                </div>
                                <div class="ty-cart-content__sku ty-sku cp-visible-tablet {if !$product.manufacturer_code} hidden{/if}">
                                    {__("cp_checkout_modifications_manufacturer_code")}: <span>{$product.manufacturer_code}</span>
                                </div> *}
                            {if $product.product_options}
                                <div class="cm-reload-{$obj_id} ty-cart-content__options" id="options_update_{$obj_id}">
                                    <input type="hidden" name="no_cache" value="no_cache" />
                                    {include file="views/products/components/product_options.tpl" product_options=$product.product_options product=$product name="cart_products" id=$key location="cart" disable_ids=$disable_ids form_name="checkout_form"}
                                    <!--options_update_{$obj_id}--></div>
                            {/if}
                            {/hook}

                            {assign var="name" value="product_options_$key"}
                            {capture name=$name}

                            {capture name="product_info_update"}
                                {hook name="checkout:product_info"}
                                    {if $product.exclude_from_calculate}
                                        <strong><span class="price">{__("free")}</span></strong>
                                    {elseif $product.discount|floatval || ($product.taxes && $settings.Checkout.tax_calculation != "subtotal")}
                                        {if $product.discount|floatval}
                                            {assign var="price_info_title" value=__("discount")}
                                        {else}
                                            {assign var="price_info_title" value=__("taxes")}
                                        {/if}
                                        <p><a data-ca-target-id="discount_{$key}" class="cm-dialog-opener cm-dialog-auto-size" rel="nofollow">{$price_info_title}</a></p>

                                        <div class="ty-group-block hidden" id="discount_{$key}" title="{$price_info_title}">
                                            <table class="ty-cart-content__more-info ty-table">
                                                <thead>
                                                    <tr>
                                                        <th class="ty-cart-content__more-info-title">{__("price")}</th>
                                                        <th class="ty-cart-content__more-info-title">{__("quantity")}</th>
                                                        {if $product.discount|floatval}<th class="ty-cart-content__more-info-title">{__("discount")}</th>{/if}
                                                        {if $product.taxes && $settings.Checkout.tax_calculation != "subtotal"}<th>{__("tax")}</th>{/if}
                                                        <th class="ty-cart-content__more-info-title">{__("subtotal")}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            {if $product.base_price > $product.price}
                                                                <span class="ty-price_old">
                                                                    {include file="common/price.tpl" value=$product.base_price class="ty-list-price"}
                                                                </span>
                                                            {/if}
                                                            <span class="ty-price_real">
                                                                {include file="common/price.tpl" value=$product.original_price span_id="original_price_`$key`" class="none"}
                                                            </span>
                                                        </td>
                                                        <td class="ty-center">{$product.amount}</td>
                                                        {if $product.discount|floatval}<td>{include file="common/price.tpl" value=$product.discount span_id="discount_subtotal_`$key`" class="none"}</td>{/if}
                                                        {if $product.taxes && $settings.Checkout.tax_calculation != "subtotal"}<td>{include file="common/price.tpl" value=$product.tax_summary.total span_id="tax_subtotal_`$key`" class="none"}</td>{/if}
                                                        <td>
                                                            {include file="common/price.tpl" span_id="product_subtotal_2_`$key`" value=$product.display_subtotal class="none"}    
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    {/if}
                                {/hook}
                            {/capture}
                            {if $smarty.capture.product_info_update|trim}
                                <div class="cm-reload-{$obj_id}" id="product_info_update_{$obj_id}">
                                    {$smarty.capture.product_info_update nofilter}
                                <!--product_info_update_{$obj_id}--></div>
                            {/if}
                            {/capture}

                            {if $smarty.capture.$name|trim}
                            <div id="options_{$key}" class="ty-product-options ty-group-block">
                                <div class="ty-group-block__arrow">
                                    <span class="ty-caret-info"><span class="ty-caret-outer"></span><span class="ty-caret-inner"></span></span>
                                </div>
                                {$smarty.capture.$name nofilter}
                            </div>
                            {/if}
                        </td>


                        <td class="ty-cart-content__product-elem ty-cart-content__product-manufacturer">
                            {$product.manufacturer}
                        </td>

                        <td class="ty-cart-content__product-elem ty-cart-content__product-manufacturer-code">
                            {$product.manufacturer_code}
                        </td>

                        <td class="ty-cart-content__product-elem ty-cart-content__price ty-cart-content__price_sub{if $product.base_price > $product.price} discount{/if} cm-reload-{$obj_id}" id="price_display_update_{$obj_id}">
                            {if $product.base_price > $product.price}
                                <span class="ty-price_old">
                                    {include file="common/price.tpl" value=$product.base_price class="ty-list-price"}
                                </span>
                            {/if}
                            <span class="ty-price_real">
                            {include file="common/price.tpl" value=$product.display_price span_id="product_price_`$key`" class="ty-sub-price"}
                            </span>
                        <!--price_display_update_{$obj_id}--></td>

                        <td class="ty-cart-content__product-elem ty-cart-content__qty {if $product.is_edp == "Y" || $product.exclude_from_calculate} quantity-disabled{/if}">
                            {if $use_ajax == true && $cart.amount != 1}
                                {assign var="ajax_class" value="cm-ajax"}
                            {/if}

                            <div class="quantity cm-reload-{$obj_id}{if $settings.Appearance.quantity_changer == "Y"} changer{/if}" id="quantity_update_{$obj_id}">
                                <input type="hidden" name="cart_products[{$key}][product_id]" value="{$product.product_id}" />

                                <input type="hidden" name="cart_products[{$key}][extra][warehouse_id]" value="{$product.extra.warehouse_id}" />


                                {if $product.exclude_from_calculate}<input type="hidden" name="cart_products[{$key}][extra][exclude_from_calculate]" value="{$product.exclude_from_calculate}" />{/if}

                                <label for="amount_{$key}"></label>
                                {if $product.is_edp == "Y" || $product.exclude_from_calculate}
                                    {$product.amount}
                                {else}
                                    {if $settings.Appearance.quantity_changer == "Y"}
                                        <div class="ty-center ty-value-changer cm-value-changer">
                                        <a class="cm-increase ty-value-changer__increase">&#43;</a>
                                    {/if}
                                    <input type="text" size="3" id="amount_{$key}" name="cart_products[{$key}][amount]" value="{$product.amount}" class="ty-value-changer__input cm-amount"{if $product.qty_step > 1} data-ca-step="{$product.qty_step}"{/if} data-ca-min-qty="{if $product.min_qty > 1}{$product.min_qty}{else}1{/if}"/>
                                    {if $settings.Appearance.quantity_changer == "Y"}
                                        <a class="cm-decrease ty-value-changer__decrease">&minus;</a>
                                        </div>
                                    {/if}
                                {/if}
                                {if $product.is_edp == "Y" || $product.exclude_from_calculate}
                                    <input type="hidden" name="cart_products[{$key}][amount]" value="{$product.amount}" />
                                {/if}
                                {if $product.is_edp == "Y"}
                                    <input type="hidden" name="cart_products[{$key}][is_edp]" value="Y" />
                                {/if}
                            <!--quantity_update_{$obj_id}--></div>
                        </td>

                        <td class="ty-cart-content__product-elem ty-cart-content__price ty-cart-content__price_total cm-reload-{$obj_id}" id="price_subtotal_update_{$obj_id}">
                            {include file="common/price.tpl" value=$product.display_subtotal span_id="product_subtotal_`$key`" class="price"}
                            {if $product.zero_price_action == "A"}
                                <input type="hidden" name="cart_products[{$key}][price]" value="{$product.base_price}" />
                            {/if}
                        <!--price_subtotal_update_{$obj_id}--></td>
                        <td class="ty-cart-content__product-elem ty-cart-content__buttons"> 
                            {if !$product.exclude_from_calculate}
                                {* {include file="addons/wishlist/views/wishlist/components/add_to_wishlist.tpl" 
                                    wishlist_but_meta="ty-btn__add-to-wish ty-btn__add-to-wish_cart"
                                    wishlist_button_type="text"
                                    wishlist_but_icon=true
                                } *}
                                <a class="{$ajax_class} ty-cart-content__product-delete ty-delete-big" 
                                    href="{"checkout.delete?vendor_id=`$vendor_id`&cart_id=`$key`&redirect_mode=`$runtime.mode`"|fn_url}" 
                                    data-ca-target-id="cart_items{$suffix_key},checkout_totals{$suffix_key},cart_status*,checkout_steps{$suffix_key},checkout_cart{$suffix_key}" 
                                    title="{__("remove")}"
                                >
                                    <span class="icon-spl-delete"></span>
                                    {__("remove")}
                                </a>
                            {/if}
                        </td>
                    </tr>
                {/if}
            {/hook}
        {/foreach}
        {/if}

        {hook name="checkout:extra_list"}
        {/hook}

    </tbody>
    </table>
<!--cart_items{$suffix_key}--></div>

{/capture}
{include file="common/mainbox_cart.tpl" title=__("cart_items") content=$smarty.capture.cartbox}
