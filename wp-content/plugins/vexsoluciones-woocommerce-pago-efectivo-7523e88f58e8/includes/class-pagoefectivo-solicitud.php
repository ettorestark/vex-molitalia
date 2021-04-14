<?php
/**
 * PagoEfectivo Gateway Pagoefectivo_solicitud.
 *
 * @since   1.0.0
 * @package PagoEfectivo_Gateway
 */

/**
 * PagoEfectivo Gateway Pagoefectivo_solicitud.
 *
 * @since 1.0.0
 */
class VGP_Pagoefectivo_Solicitud {

    protected $_xml;

    /*
     * @constructor
     */
    public function __construct(){
        $this->_xml = new SimpleXMLElement( '<?xml version="1.0" encoding="utf-8" ?><SolPago></SolPago>' );
    }

    /*
     * @destructor
     */
    public function __destruct(){
        $this->_xml = null;
    }

    private function array_to_xml( array $arr, SimpleXMLElement &$xml ){
        foreach( $arr as $k => $v ){
            is_array( $v ) ? $this->array_to_xml( $v, $xml->addChild( $k ) ) : $xml->addChild( $k, $v );
        }

        return $xml;
    }

    /*
     * Añade los tags iniciales del XML
     * @param array $contenido Contenido a añadir formato: array('tag' => 'valor')
     * @return PagoEfectivo_Solicitud el objeto contenedor
     */
    public function convertToXml( $be_solicitud ){
        $this->array_to_xml( $be_solicitud, $this->_xml );
        return $this;
    }

    public function toXml(){
        return $this->_xml->asXML();
    }

    function __toString(){
        return $this->toXml();
    }
}
