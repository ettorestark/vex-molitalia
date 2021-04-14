<?php
/**
 * PagoEfectivo Gateway Pagoefectivo Bancaporinternet.
 *
 * @since   1.0.0
 * @package PagoEfectivo_Gateway
 */

/**
 * PagoEfectivo Gateway Pagoefectivo Bancaporinternet.
 *
 * @since 1.0.0
 */
class VGP_Gateway_Pagoefectivo_Deposito extends WC_Payment_Gateway {

    function __construct() {
        $this->id          = "pagoefectivo-deposito";
        $this->title       = 'Depósito en efectivo (PagoEfectivo)';
        $this->description = 'Acércate a cualquier punto del BBVA, BCP, INTERBANK, SCOTIABANK y BANBIF. Agentes corresponsales KASNET, WESTERUNION – Pago de Servicios y FULLCARGA.';

        $this->icon = vexsoluciones_gateway_pagoefectivo()->url( 'assets/images/rsz_dos.png' );
    }
}
