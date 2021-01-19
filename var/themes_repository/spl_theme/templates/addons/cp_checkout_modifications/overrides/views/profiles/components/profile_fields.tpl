{if $show_email}
    <div class="ty-control-group">
        <label for="{$id_prefix}elm_email" class="cm-required cm-email">{__("email")}<i>*</i></label>
        <input type="text" id="{$id_prefix}elm_email" name="user_data[email]" size="32" value="{$user_data.email}" class="ty-input-text {$_class}" {$disabled_param} />
    </div>
{else}

{$fields = []}

{if !$exclude && !$include}
    {$fields = $profile_fields.$section}
{else}
    {foreach $profile_fields.$section as $key => $field}
        {if $include}
            {if in_array($field.field_name, $include)}
                {$fields[$key] = $field}
            {/if}
        {elseif $exclude}
            {if !in_array($field.field_name, $exclude)}
                {$fields[$key] = $field}
            {/if}
        {/if}
    {/foreach}
{/if} 

{if $fields}

{*CP: cp_checkout_modification *}
{if $user_data.cp_is_matching_addresses == "N"}
    {$actual_same_as_billing = false}
{/if}
{if $actual_address_flag && $runtime.mode == "add"}
    <div class="ty-profile-field__switch ty-address-switch clearfix hidden">
        <div class="ty-profile-field__switch-label">{__("actual_same_as_billing")}</div>
        <div class="ty-profile-field__switch-actions">
            <input class="radio cm-switch-availability cm-switch-inverse cm-switch-visibility" type="radio" name="actual_same_as_billing" value="1" id="sw_{$body_id}_suffix_yes" {if $actual_same_as_billing}checked="checked"{/if} /><label for="sw_{$body_id}_suffix_yes">{__("yes")}</label>
            <input class="radio cm-switch-availability cm-switch-visibility" type="radio" name="actual_same_as_billing" value="0" id="sw_{$body_id}_suffix_no" {if !$actual_same_as_billing}checked="checked"{/if} /><label for="sw_{$body_id}_suffix_no">{__("no")}</label>
        </div>
    </div>
    
    {if $actual_same_as_billing}
        {$disabled_by_default = true}
    {/if}
{/if}
{*CP: end *}


{if $address_flag}
    <div class="ty-profile-field__switch ty-address-switch clearfix">
        <div class="ty-profile-field__switch-label">{if $section == "S"}{__("shipping_same_as_billing")}{else}{__("text_billing_same_with_shipping")}{/if}</div>
        <div class="ty-profile-field__switch-actions">
            <input class="radio cm-switch-availability cm-switch-inverse cm-switch-visibility" type="radio" name="ship_to_another" value="0" id="sw_{$body_id}_suffix_yes" {if !$ship_to_another}checked="checked"{/if} /><label for="sw_{$body_id}_suffix_yes">{__("yes")}</label>
            <input class="radio cm-switch-availability cm-switch-visibility" type="radio" name="ship_to_another" value="1" id="sw_{$body_id}_suffix_no" {if $ship_to_another}checked="checked"{/if} /><label for="sw_{$body_id}_suffix_no">{__("no")}</label>
        </div>
    </div>
{else}
    <input type="hidden" name="ship_to_another" value="1" />
{/if}

{if ($address_flag && !$ship_to_another && ($section == "S" || $section == "B")) || $disabled_by_default}
    {assign var="disabled_param" value="disabled=\"disabled\""}
    {assign var="_class" value="disabled"}
    {assign var="hide_fields" value=true}
{else}
    {assign var="disabled_param" value=""}
    {assign var="_class" value=""}
{/if}
{if !$hide_clearfix}
<div class="clearfix">
{/if}
    {if $body_id || $grid_wrap}
        <div id="{$body_id}" class="{if $hide_fields}hidden{/if}">
            <div class="{$grid_wrap}">
    {/if}

    {if !$nothing_extra}
        {include file="common/subheader.tpl" title=$title}
    {/if}

    {$default_data_name = $default_data_name|default:"user_data"}
    {$profile_data = $profile_data|default:$user_data}

    {foreach from=$fields item=field name="profile_fields"}
    {if $field.field_name && $field.is_default == 'Y'}
        {assign var="data_name" value=$default_data_name}
        {assign var="data_id" value=$field.field_name}
        {assign var="value" value=$profile_data.$data_id}
    {else}
        {assign var="data_name" value="`$default_data_name`[fields]"}
        {assign var="data_id" value=$field.field_id}
        {assign var="value" value=$profile_data.fields.$data_id}
    {/if}

    {assign var="skip_field" value=false}
    {if $section == "S" || $section == "B"}
        {if $section == "S"}
            {assign var="_to" value="B"}
        {else}
            {assign var="_to" value="S"}
        {/if}
        {if !$profile_fields.$_to[$field.matching_id]}
            {assign var="skip_field" value=true}
        {/if}
    {/if}

    {hook name="profiles:profile_fields"}

    {*CP: cp_checkout_modification *}
    {if ($runtime.mode == "add" && $field.cp_add_hide == 'Y') || ($runtime.mode == "update" && $field.cp_update_hide == 'Y')}
        {$cp_hide_field = true}
    {else}
        {$cp_hide_field = false}
    {/if}
    {if ($runtime.mode == "add" && $field.cp_add_disabled == 'Y') || ($runtime.mode == "update" && $field.cp_update_disabled == 'Y')}
        {$disabled_param = $disabled_param|cat:" readonly='readonly'"}
    {else}
        {$disabled_param = false}
    {/if}
    {if $runtime.mode == "add" && $field.field_name != "inn" && $cp_add_disabled}
        {$disabled_class = true}
    {else}
        {$disabled_class = false}
    {/if}
    <div class="ty-control-group ty-profile-field__item{if $field.wrapper_class} {$field.wrapper_class}{/if}{if $cp_hide_field} hidden{/if}{if $disabled_class} ty-control-group_disabled{/if}">
    {*CP: end *}
    {if ($pref_field_name != $field.description || $field.required == "Y") && $field.field_type != "ProfileFieldTypes::VENDOR_TERMS"|enum}
        <label
            for="{$id_prefix}elm_{$field.field_id}"
            class="ty-control-group__title cm-profile-field {if $field.autocomplete_type == "phone-full" || $field.field_type == "ProfileFieldTypes::PHONE"|enum}cm-mask-phone-label{/if} {if $field.required == "Y"}cm-required{/if}{if $field.field_type == "Z"} cm-zipcode{/if}{if $field.field_type == "E"} cm-email{/if} {if $field.field_type == "Z"}{if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}{/if}"
        >{$field.description}</label>
    {/if}

        {if $field.field_type == "ProfileFieldTypes::STATE"|enum}
            {$_country = $settings.Checkout.default_country}
            {$_state = $value|default:$settings.Checkout.default_state}

            <select {if $field.autocomplete_type}x-autocompletetype="{$field.autocomplete_type}"{/if} id="{$id_prefix}elm_{$field.field_id}" class="ty-profile-field__select-state cm-state {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}{if $field.class} ty-{$field.class}{/if} {if !$skip_field}{$_class}{/if}" name="{$data_name}[{$data_id}]" {if !$skip_field}{$disabled_param nofilter}{/if} data-field-name="{$field.field_name}">
                {if $field.required !== "Y"}
                    <option value="">- {__("select_state")} -</option>
                {/if}
                {if $states && $states.$_country}
                    {foreach from=$states.$_country item=state}
                        <option {if $_state == $state.code}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
                    {/foreach}
                {/if}
            </select>

            <input {if $field.autocomplete_type}x-autocompletetype="{$field.autocomplete_type}"{/if} {if $field.numeric_field_type == "Y"} type="number"{else} type="text"{/if} id="elm_{$field.field_id}_d" name="{$data_name}[{$data_id}]" size="32" maxlength="64" value="{$_state}" disabled="disabled" class="cm-state {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if} ty-input-text hidden {if $_class}disabled{/if}"/>

        {elseif $field.field_type == "ProfileFieldTypes::COUNTRY"|enum}
            {assign var="_country" value=$value|default:$settings.Checkout.default_country}
            <select {if $field.autocomplete_type}x-autocompletetype="{$field.autocomplete_type}"{/if} id="{$id_prefix}elm_{$field.field_id}" class="ty-profile-field__select-country cm-country {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}{if $field.class} ty-{$field.class}{/if} {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}" name="{$data_name}[{$data_id}]" {if !$skip_field}{$disabled_param nofilter}{/if} data-field-name="{$field.field_name}" >
                {hook name="profiles:country_selectbox_items"}
                {if $field.required !== "Y"}
                    <option value="">- {__("select_country")} -</option>
                {/if}
                {foreach from=$countries item="country" key="code"}
                <option {if $_country == $code}selected="selected"{/if} value="{$code}">{$country}</option>
                {/foreach}
                {/hook}
            </select>

        {elseif $field.field_type == "ProfileFieldTypes::CHECKBOX"|enum}
            <input type="hidden" name="{$data_name}[{$data_id}]" value="N" {if !$skip_field}{$disabled_param nofilter}{/if} />
            <input type="checkbox" id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" value="Y" {if $value == "Y"}checked="checked"{/if} class="checkbox {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}{if $field.class} ty-{$field.class}{/if}" {if !$skip_field}{$disabled_param nofilter}{/if} />

        {elseif $field.field_type == "ProfileFieldTypes::TEXT_AREA"|enum}
            <textarea {if $field.autocomplete_type}x-autocompletetype="{$field.autocomplete_type}"{/if} class="ty-input-textarea {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}{if $field.class} ty-{$field.class}{/if}" id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" cols="32" rows="3" {if !$skip_field}{$disabled_param nofilter}{/if}>{$value}</textarea>

        {elseif $field.field_type == "ProfileFieldTypes::DATE"|enum}
            {if !$skip_field}
                {include file="common/calendar.tpl" date_id="`$id_prefix`elm_`$field.field_id`" date_name="`$data_name`[`$data_id`]" date_val=$value extra=$disabled_param}
            {else}
                {include file="common/calendar.tpl" date_id="`$id_prefix`elm_`$field.field_id`" date_name="`$data_name`[`$data_id`]" date_val=$value}
            {/if}

        {elseif $field.field_type == "ProfileFieldTypes::SELECT_BOX"|enum}
            <select {if $field.autocomplete_type}x-autocompletetype="{$field.autocomplete_type}"{/if} id="{$id_prefix}elm_{$field.field_id}" class="ty-profile-field__select {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}{if $field.class} ty-{$field.class}{/if}" name="{$data_name}[{$data_id}]" {if !$skip_field}{$disabled_param nofilter}{/if}>
                {*CP: cp_checkout_modification *}
                {if $field.required != "Y" 
                    || $field.field_name == 'activity_field'
                }
                {*CP: end *}
                <option value="">--</option>
                {/if}
                {foreach from=$field.values key=k item=v}
                <option {if $value == $k}selected="selected"{/if} value="{$k}">{$v}</option>
                {/foreach}
            </select>

        {elseif $field.field_type == "ProfileFieldTypes::RADIO"|enum}
            <div id="{$id_prefix}elm_{$field.field_id}">
                {foreach from=$field.values key=k item=v name="rfe"}
                <input class="radio {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if} {$id_prefix}elm_{$field.field_id}{if $field.class} ty-{$field.class}{/if}" type="radio" id="{$id_prefix}elm_{$field.field_id}_{$k}" name="{$data_name}[{$data_id}]" value="{$k}" {if (!$value && $smarty.foreach.rfe.first) || $value == $k}checked="checked"{/if} {if !$skip_field}{$disabled_param nofilter}{/if} /><span class="radio">{$v}</span>
                {/foreach}
            </div>

        {elseif $field.field_type == "ProfileFieldTypes::ADDRESS_TYPE"|enum}
            <input class="radio {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if} {$id_prefix}elm_{$field.field_id}" type="radio" id="{$id_prefix}elm_{$field.field_id}_residential" name="{$data_name}[{$data_id}]" value="residential" {if !$value || $value == "residential"}checked="checked"{/if} {if !$skip_field}{$disabled_param nofilter}{/if} /><span class="radio">{__("address_residential")}</span>
            <input class="radio {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if} {$id_prefix}elm_{$field.field_id}" type="radio" id="{$id_prefix}elm_{$field.field_id}_commercial" name="{$data_name}[{$data_id}]" value="commercial" {if $value == "commercial"}checked="checked"{/if} {if !$skip_field}{$disabled_param nofilter}{/if} /><span class="radio">{__("address_commercial")}</span>

        {elseif $field.field_type == "ProfileFieldTypes::VENDOR_TERMS"|enum}

            {include file="views/profiles/components/vendor_terms.tpl"}
        {elseif 
                $field.field_name == 's_area'
                || $field.field_name == 's_street'
                || $field.field_name == 's_home'
                || $field.field_name == 's_corp'
                || $field.field_name == 's_office'
                || $field.field_name == 'b_area'
                || $field.field_name == 'b_street'
                || $field.field_name == 'b_home'
                || $field.field_name == 'b_corp'
                || $field.field_name == 'b_office'
        }
            <input
                {if $field.autocomplete_type}x-autocompletetype="{$field.autocomplete_type}"{/if}
                {if $field.numeric_field_type == "Y"}
                    type="number"
                {else}
                    type="text"
                {/if}
                id="{$id_prefix}elm_{$field.field_name}"
                name="{$data_name}[{$data_id}]"
                size="32"
                value="{$value}"
                class="ty-input-text{if ($field.autocomplete_type == "phone-full") || ($field.field_type == "ProfileFieldTypes::PHONE"|enum)} cm-mask-phone{/if} {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if} {if $smarty.foreach.profile_fields.index == 0} cm-focus{/if}{if $field.class} ty-{$field.class}{/if}" 
                {if !$skip_field}{$disabled_param nofilter}{/if}
                data-field-name="{$field.field_name}"
                {if $field.cp_edit_if_empty == 'Y'}data-edit-if-empty="1"{/if}
            />
        {else}  {* Simple input *}
            <input
                {if $field.autocomplete_type}x-autocompletetype="{$field.autocomplete_type}"{/if}
                {if $field.numeric_field_type == "Y"}
                    type="number"
                {else}
                    type="text"
                {/if}
                id="{$id_prefix}elm_{$field.field_id}"
                name="{$data_name}[{$data_id}]"
                size="32"
                value="{$value}"
                class="ty-input-text{if ($field.autocomplete_type == "phone-full") || ($field.field_type == "ProfileFieldTypes::PHONE"|enum)} cm-mask-phone{/if} {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if} {if $smarty.foreach.profile_fields.index == 0} cm-focus{/if} {if $field.class} ty-{$field.class}{/if}" {if !$skip_field}{$disabled_param nofilter}{/if}
                data-field-name="{$field.field_name}"
                {if $field.cp_edit_if_empty == 'Y'}data-edit-if-empty="1"{/if}
            />
        {/if}
    
    {assign var="pref_field_name" value=$field.description}
    {if $show_hint && $field.field_name == "inn"}
        <p class="cp-inn-hint">{__("cp_inn_hint")}</p>
    {/if}
        {if $field.field_name == "b_address"}
            <div class="ty-profile-field__pseudo-switch">
                <input class="pseudo-switch-address__input hidden" type="checkbox" id="pseudo_switch_address" name="pseudo_switch_address" checked="checked">
                <label class="pseudo-switch-address__label" for="pseudo_switch_address"><span>{__("cp_actual_same_as_billing")}</span></label>
            </div>
        {/if}
    </div>
    {/hook}
    {/foreach}

    {if $body_id || $grid_wrap}
            </div>
        </div>
    {/if}
{if !$hide_clearfix} 
</div>
{/if}

{/if}
{/if}