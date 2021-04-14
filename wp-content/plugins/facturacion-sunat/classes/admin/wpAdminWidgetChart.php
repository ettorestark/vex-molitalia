<?PHP

//namespace vexfacturacionelectronica;


class wpAdminWidgetChart extends \WP_Widget {
  /**
  * To create the example widget all four methods will be
  * nested inside this single instance of the WP_Widget class.
  **/

  public function __construct(){

  		$widget_options = array(
  	     'classname' => 'example_widget',
  	     'description' => 'Facturación electrónica Widget',
  	   );

  	   parent::__construct( 'example_widget', 'Example Widget', $widget_options );

  }

  public function widget( $args, $instance ){


 // 	$title =  'Facturación electrónica'; //apply_filters( 'widget_title', $instance[ 'title' ] );
  	$razon_social = get_option('vexfe_razon_social'); // get_bloginfo( 'name' );
  	$ruc =  get_option('vexfe_ruc'); // get_bloginfo( 'description' );

  //	echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];

  	?>

  	<p><strong>Empresa:</strong> <?php echo $razon_social ?></p>
  	<p><strong>RUC:</strong> <?php echo $ruc ?></p>


    <canvas id="myCanvasEl" style=" width: 100%; height: 300px;"></canvas>

    <div style="margin-top:20px;">
      <span> N° de facturas pendientes de envio: </span>
      <span> <b> 0 </b> </span>

      <br/>
      <span> N° de facturas no comprobadas: </span>
      <span> <b> 0 </b> </span>

      <br/>
      <span> Boletas emitidas el día de ayer: </span>
      <span> <b> 0 </b> </span>

      <br/>

      <span> Total de comprobantes enviados: </span>
      <span> <b> 0 </b> </span>

    </div>


  	<?php echo $args['after_widget'];


  }

  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
    </p><?php
  }

  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
    return $instance;
  }


}
