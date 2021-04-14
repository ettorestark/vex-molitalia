<?php

/*
 * * Woocommerce Peru Ubigeos
 * * https://www.pasarelasdepagos.com/
 * *
 * * Copyright (c) 2019 vexsoluciones
 * * Licensed under the GPLv2+ license.
 */

class VexSolucionesUbigeosFormFields {
    
    const PERU = "PE";
    
    private static $checkoutFields;
    
    function __construct() {
        // add_action("woocommerce_form_field_ubigeo_map", [$this, "olvaUbigeoMapField"], 10, 4);
        add_action("woocommerce_checkout_fields", [$this, "addCheckoutFields"], 10, 1);
        add_action("woocommerce_checkout_update_order_meta", [$this, "checkoutUpdateOrderMeta"], 10, 1);
        add_action("woocommerce_admin_order_data_after_billing_address", [$this, "showCheckoutFieldsInAdminOrderData"], 10, 1);
        add_filter("woocommerce_order_formatted_billing_address", [$this, "addOrderBillingAddressDetail"], 10, 2);
        add_filter("woocommerce_order_formatted_shipping_address", [$this, "addOrderShippingAddressDetail"], 10, 2);
        add_filter("woocommerce_states", [$this, "removeImcopatibleStates"], 10, 1);
    }
    
    public function addCheckoutFields($fields) {
        foreach(self::getCheckoutFields() as $fieldName => $fieldData) {
            $placeIn = $fieldData["placeIn"];
            unset($fieldData["placeIn"]);
            $fields[$placeIn][$fieldName] = $fieldData;
        }
        return $fields;
    }
    
    public function removeImcopatibleStates($states) {
        unset($states["PE"]["CAL"]);
        unset($states["PE"]["LMA"]);
        return $states;
    }

    public static function getCheckoutFields() {
        if(!isset(self::$checkoutFields)) 
            self::$checkoutFields = self::createCheckoutFields();
        return self::$checkoutFields;
    }

    private static function createCheckoutFields() {
        /* DNI */
        $fieldDni = [
            "label" => __("DNI", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN),
            "class" => ["form-row-wide"],
            "placeholder" => "DNI:",
            "priority" => 5,
            "required" => true
        ];
        $fields["billing_dni"] = $fieldDni;
        $fields["billing_dni"]["placeIn"] = "billing";
        $fields["shipping_dni"] = $fieldDni;
        $fields["shipping_dni"]["placeIn"] = "shipping";
        /* Departaments */
        /* $optionsDepartaments = [
            'blank' => __("Seleccione un departamento...", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN)
        ];
        $departaments = VexSolucionesUbigeos::getDepartaments();
        if($departaments)
            foreach($departaments as $province)
                $optionsDepartaments[$province["id_ubigeo"]] = $province["nombre_ubigeo"];
        $fieldDepartament = [
            'type'          => 'select',
            'class'         => ["ubigeo_select_departament"],
            'label'         => __('Departamento', Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN),
            'options'       => $optionsDepartaments,
            "priority" => 85,
            "required" => true
        ];
        $fields["billing_ubigeo_departament"] = $fieldDepartament;
        $fields["billing_ubigeo_departament"]["placeIn"] = "billing";
        $fields["shipping_ubigeo_departament"] = $fieldDepartament;
        $fields["shipping_ubigeo_departament"]["placeIn"] = "shipping"; */
        /* Provinces */
        $optionsProvinces = [
            'blank' => __("Seleccione una provincia...", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN)
        ];
        $fieldProvince = [
            'type'          => 'select',
            'class'         => ["ubigeo_select_province"],
            'label'         => __('Provincia', Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN),
            'options'       => $optionsProvinces,
            "priority" => 85,
            "required" => true
        ];
        $fields["billing_ubigeo_province"] = $fieldProvince;
        $fields["billing_ubigeo_province"]["placeIn"] = "billing";
        $fields["shipping_ubigeo_province"] = $fieldProvince;
        $fields["shipping_ubigeo_province"]["placeIn"] = "shipping";
        /* Districts */
        $optionsDistricts = [
            'blank' => __("Seleccione un distrito...", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN)
        ];
        $fieldDistrict = [
            'type'          => 'select',
            'class'         => ["ubigeo_select_district"],
            'label'         => __('Distrito', Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN),
            'options'       => $optionsDistricts,
            "priority" => 86,
            "required" => true
        ];
        $fields["billing_ubigeo_district"] = $fieldDistrict;
        $fields["billing_ubigeo_district"]["placeIn"] = "billing";
        $fields["shipping_ubigeo_district"] = $fieldDistrict;
        $fields["shipping_ubigeo_district"]["placeIn"] = "shipping";
        /* Map */
        /* $fieldDni = [
            "type" => "ubigeo_map",
            "priority" => 87,
        ];
        $fields["billing_ubigeo_map"] = $fieldDni;
        $fields["billing_ubigeo_map"]["placeIn"] = "billing";
        $fields["shipping_ubigeo_map"] = $fieldDni;
        $fields["shipping_ubigeo_map"]["placeIn"] = "shipping"; */
        return $fields;
    }
    
    public static function isPeru() {
        $country = WC()->customer->get_shipping_country();
        return $country == self::PE;
    }
    
    public function checkoutUpdateOrderMeta($orderId) {
        if(WC()->customer->get_shipping_country() == "PE") {
            $status = 0;
            foreach(self::getCheckoutFields() as $fieldName => $fieldData)  {
                if($fieldData["type"] == "ubigeo_map")
                    continue;
                $status += $this->validateFieldAndUpdateMetaTag($orderId, $fieldName, $fieldData);
            }
            if($status)
                throw new Exception();
        }
    }
    
    private function validateFieldAndUpdateMetaTag($orderId, $fieldName, $fieldData) {
        $shipToDifferentAddress = $_POST["ship_to_different_address"] == 1;
        if(($fieldData["placeIn"] === "shipping" && $shipToDifferentAddress)
                || ($fieldData["placeIn"] === "billing" && !$shipToDifferentAddress)) {
            if(!empty($_POST[$fieldName]) && $_POST[$fieldName] != "blank")
                update_post_meta($orderId, $fieldName, sanitize_text_field($_POST[$fieldName])); 
            else {
                $label = $fieldData["label"];
                if($fieldData["placeIn"] === "billing")
                    $label .= " " . __("Facturación", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN);
                else 
                    $label .= " " . __("Envío", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN);
                wc_add_notice(sprintf(__("El campo %s esta vacío.", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN), $label), "error");
                return 1;
            }
        }
        return 0;
    }
    
    public function showCheckoutFieldsInAdminOrderData($order) {
        foreach(self::getCheckoutFields() as $fieldName => $fieldData) {
            $fieldValue = get_post_meta($order->id, $fieldName, true);
            switch($fieldName) {
                case "billing_state":
                case "shipping_state":
                    $fieldValue = VexSolucionesUbigeos::getDepartamentByShortName($fieldValue)["nombre_ubigeo"];
                    break;
                case "billing_ubigeo_province":
                case "shipping_ubigeo_province":
                    $fieldValue = VexSolucionesUbigeos::getProvinceByKey($fieldValue)["nombre_ubigeo"];
                    break;
                case "billing_ubigeo_district":
                case "shipping_ubigeo_district":
                    $fieldValue = VexSolucionesUbigeos::getDistrictByKey($fieldValue)["nombre_ubigeo"];
                    break;
            }
            if(!empty($fieldValue)):
                $label = $fieldData["label"];
                if($fieldData["placeIn"] === "billing")
                    $label .= " " . __("Facturación", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN);
                else 
                    $label .= " " . __("Envío", Vexsoluciones_Peru_Ubigeo::TEXT_DOMAIN); ?>
                <p>
                    <strong><?php echo $label; ?>:</strong><br> <?php echo $fieldValue; ?>
                </p>
                <?php
            endif;
        }
    }
    
    public function addOrderBillingAddressDetail($billing, $order) {
        $this->addOrderAddressDetail("billing", $billing, $order);
        return $billing;
    }
    
    public function addOrderShippingAddressDetail($shippingAddress, $order) {
        $this->addOrderAddressDetail("shipping", $shippingAddress, $order);
        return $shippingAddress;
    }
    
    protected function addOrderAddressDetail($placeIn, &$address, $order) {
        foreach(self::getCheckoutFields() as $fieldName => $fieldData) {
            if($fieldData["placeIn"] === $placeIn) {
                $fieldValue = get_post_meta($order->get_id(), $fieldName, true);
                switch($fieldName) {
                    case "billing_state":
                    case "shipping_state":
                        $fieldValue = VexSolucionesUbigeos::getDepartamentByShortName($fieldValue)["nombre_ubigeo"];
                        break;
                    case "billing_ubigeo_province":
                    case "shipping_ubigeo_province":
                        $fieldValue = VexSolucionesUbigeos::getProvinceByKey($fieldValue)["nombre_ubigeo"];
                        break;
                    case "billing_ubigeo_district":
                    case "shipping_ubigeo_district":
                        $fieldValue = VexSolucionesUbigeos::getDistrictByKey($fieldValue)["nombre_ubigeo"];
                        break;
                }
                if(!empty($fieldValue))
                    $address[$fieldName] = $fieldValue;
            }
        }
    }
    
    /* public function olvaUbigeoMapField($field, $key, $args, $value) { ?>
        <div id="<?php echo $key; ?>_field" jstcache="0" class="form-row olva_location_map" style="display: none;">
            <div class="olva-location-field-map" style="height: 300px;"></div>
            <div class="row hidden">
                <div class="col-sm-6">
                    <fieldset>
                        <label>Latitud</label>
                        <div class="field">
                            <input class="geo_latitude" name="<?php echo $key; ?>_lat" value="<?php echo $value; ?>" type="text">
                        </div>
                    </fieldset>
                </div>
                <div class="col-sm-6">
                    <fieldset>
                        <label>Longitud</label>
                        <div class="field">
                            <input class="geo_longitude" name="<?php echo $key; ?>_lgt" value="<?php echo $value; ?>" type="text">
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    <?php
    } */
    
}