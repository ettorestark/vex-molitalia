jQuery(document).ready(function($){



	//$( '<li id="menu-item-9999" class="menu-item menu-item-type-custom menu-item-object-custom  menu-item-1115"> <a href="#"> Monedero: 250  puntos </a></li>' ).insertAfter( "#menu-item-1115" );

	//$('.social-icons.follow-icons').css('display', 'none');

	$('#solicitar_factura').on('change', function(){


		if( $(this).is(':checked') )
		{
			$('.datos-facturacion').show()
				.find('input').prop('required', true);
			return;
		}

		$('.datos-facturacion').hide()
			.find('input').prop('required', false);

	}).change();


});
