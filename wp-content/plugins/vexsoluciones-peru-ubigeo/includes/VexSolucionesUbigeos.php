<?php

/*
 * * Woocommerce Olva Courier
 * * https://www.pasarelasdepagos.com/shop/peru/woocommerce/plugin-woocommerce-olva/
 * *
 * * Copyright (c) 2019 vexsoluciones
 * * Licensed under the GPLv2+ license.
 */

defined( 'ABSPATH' ) || exit;

class VexSolucionesUbigeos {
    
    public static function getDepartaments() {
        return VexSolucionesUtils::getFromJsonFile(Vexsoluciones_Peru_Ubigeo::DIR . "/assets/json/departamentos.json");
    }
    
    public static function getDepartamentByKey($key) {
        $departaments = self::getDepartaments();
        if($departaments) {
            foreach($departaments as $departament)
                if($departament["id_ubigeo"] == $key)
                    return $departament;
        }
        return false;
    }
    
    public static function getDepartamentByShortName($name) {
        $departaments = self::getDepartaments();
        if($departaments) {
            foreach($departaments as $departament)
                if($departament["woo_state"] == $name)
                    return $departament;
        }
        return false;
    }
    
    public static function departamentSelectOptions($postDepartament=false) {
        $departaments = VexSolucionesUbigeos::getDepartaments();
        if($departaments):
            foreach($departaments as $departament): ?>
                <option value="<?php echo $departament["id_ubigeo"]; ?>" <?php echo $departament["id_ubigeo"] == $postDepartament ? "selected" : ""; ?>><?php echo $departament["nombre_ubigeo"]; ?></option>
            <?php
            endforeach;
        endif;
    }
    
    public static function getProvinces() {
        return VexSolucionesUtils::getFromJsonFile(Vexsoluciones_Peru_Ubigeo::DIR . "/assets/json/provincias.json");
    }
    
    public static function getProvinceByKey($key) {
        $vacaProvinces = self::getProvinces();
        if($vacaProvinces) {
            foreach($vacaProvinces as $provinces)
                foreach($provinces as $province)
                    if($province["id_ubigeo"] == $key)
                        return $province;
        }
        return false;
    }
    
    public static function provincesSelectOptions($postDepartament, $postProvince=false) {
        $provinces = VexSolucionesUbigeos::getProvinces();
        if($provinces):
            foreach($provinces[$postDepartament] as $province): ?>
                <option value="<?php echo $province["id_ubigeo"]; ?>" <?php echo $province["id_ubigeo"] == $postProvince ? "selected" : ""; ?>><?php echo $province["nombre_ubigeo"]; ?></option>
            <?php
            endforeach;
        endif;
    }
    
    public static function getDistricts() {
        return VexSolucionesUtils::getFromJsonFile(Vexsoluciones_Peru_Ubigeo::DIR . "/assets/json/distritos.json");
    }
    
    public static function getDistrictByKey($key) {
        $vacaDistricts = self::getDistricts();
        if($vacaDistricts) {
            foreach($vacaDistricts as $districts)
                foreach($districts as $district)
                    if($district["id_ubigeo"] == $key)
                        return $district;
        }
        return false;
    }
    
    public static function districtsSelectOptions($postProvince, $postDistrict=false) {
        $districts = VexSolucionesUbigeos::getDistricts();
        if($districts):
            foreach($districts[$postProvince] as $district): ?>
                <option value="<?php echo $district["id_ubigeo"]; ?>" <?php echo $district["id_ubigeo"] == $postDistrict ? "selected" : ""; ?>><?php echo $district["nombre_ubigeo"]; ?></option>
            <?php
            endforeach;
        endif;
    }
    
    public static function getOlvaUbigeos() {
        return VexSolucionesUtils::getFromJsonFile(Vexsoluciones_Peru_Ubigeo::DIR . "/assets/json/ubigeos_peru.json");
    }
    
    public static function getOlvaUbigeoByKey($key) {
        $ubigeos = self::getOlvaUbigeos();
        if($ubigeos) {
            foreach($ubigeos as $ubigeo)
                if($ubigeo["nombre_completo"] == $key)
                    return $ubigeo;
        }
        return false;
    }
    
}
