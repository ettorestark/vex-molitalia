<?php

/**
 * PagoEfectivo Gateway Pagoefectivo_service.
 *
 * @since   1.0.0
 * @package PagoEfectivo_Gateway
 */

/**
 * PagoEfectivo Gateway Pagoefectivo_service.
 *
 * @since 1.0.0
 */
class VGP_Pagoefectivo_Service extends VGP_Pagoefectivo_Service_Abstract {

    public static $_instance;

    protected $_options = array(
        'apiKey' => null,
        'url2' => null,
        'crypto' => array(
            'securityPath' => null,
            'publicKey' => null,
            'privateKey' => null,
            'url' => null
        ),

        'gen' => array(
            'url' => null
        ),

        'mailAdmin' => null,
        'medioPago' => null
        // 'imgbarra' => PE_WSCIPIMG
    );

    protected $_crypto;

    protected $_lastPayRequest;

    /**
     * Constructor.
     *
     * @since 1.0.0
     *
     * @param PagoEfectivo_Gateway $plugin
     *            Main plugin object.
     */
    public function __construct( array $options ){
        $this->_options = array_merge( $this->_options, $options );
        $this->_crypto = VGP_Pagoefectivo_Crypto::getInstance( $this->_options[ 'crypto' ] );
    }

    public function GenerarCip( $be_solicitud ){
        $xml = new VGP_Pagoefectivo_Solicitud();
        $xml->convertToXml( $be_solicitud );
        return $this->solicitarPago( $xml );
    }

    /*
     * Solicitar Pago
     * @param string $xml XML de envio de solicitud de pago
     * @return SimpleXMLElement Resultado de Servicio Ejm:
     * SimpleXMLElement Object
     * (
     * [iDResSolPago] => 33
     * [CodTrans] => 3300020
     * [Token] => 2a3848a4-183a-490c-813a-40d90e82ef96
     * [Fecha] => 21/02/2012 11:26:27 a.m.
     * )
     */
    public function solicitarPago( $xml ){
        $info = $this->_loadService( 'GenerarCIPMod1', array(
            'request' => array(
                'CodServ' => $this->_options[ 'apiKey' ],
                'Firma' => $this->_crypto->signer( $xml ),
                'Xml' => $this->_crypto->encrypt( $xml )
            )
        ) );

        if ( $info != false ) {
            $info = $info->GenerarCIPMod1Result;

            if ( $info->Estado != 1 )
                throw new Exception( 'Pago Efectivo : ' . $info->Mensaje );

            $a = simplexml_load_string( $this->_crypto->decrypt( $info->Xml ) );
            $a->Mensaje = $info->Mensaje;
            $a->Estado = $info->Estado;
            return $a;
        }
    }

    /*
     * Consultar Pago
     * @param string $xml XML de envio de solicitud de pago
     * @return SimpleXMLElement Resultado de Servicio Ejm:
     */
    public function consultarCip( $CIP ){
        $info = $this->_loadService( 'ConsultarCIPMod1', array(
            'request' => array(
                'CodServ' => $this->_options[ 'apiKey' ],
                'Firma' => $this->_crypto->signer( $CIP ),
                'CIPS' => $this->_crypto->encrypt( ( string ) $CIP )
            )
        ) );

        if ( $info != false ) {
            $info = $info->ConsultarCIPMod1Result;
            // Desencriptar el xml de la consulta
            $xml = simplexml_load_string( $this->desencriptarData( $info->XML ) );
            $info->Estado = $info->Estado;
            $info->CIPs = $xml;
        }

        return $info;
    }

    public function desencriptarData( $string ){
        return $this->_crypto->decrypt( $string );
    }
}
