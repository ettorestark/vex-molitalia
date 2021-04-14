

window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

(function(global) {
	var Months = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	];

	var COLORS = [
		'#4dc9f6',
		'#f67019',
		'#f53794',
		'#537bc4',
		'#acc236',
		'#166a8f',
		'#00a950',
		'#58595b',
		'#8549ba'
	];

	var Samples = global.Samples || (global.Samples = {});
	var Color = global.Color;

	Samples.utils = {
		// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
		srand: function(seed) {
			this._seed = seed;
		},

		rand: function(min, max) {
			var seed = this._seed;
			min = min === undefined ? 0 : min;
			max = max === undefined ? 1 : max;
			this._seed = (seed * 9301 + 49297) % 233280;
			return min + (this._seed / 233280) * (max - min);
		},

		numbers: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 1;
			var from = cfg.from || [];
			var count = cfg.count || 8;
			var decimals = cfg.decimals || 8;
			var continuity = cfg.continuity || 1;
			var dfactor = Math.pow(10, decimals) || 0;
			var data = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = (from[i] || 0) + this.rand(min, max);
				if (this.rand() <= continuity) {
					data.push(Math.round(dfactor * value) / dfactor);
				} else {
					data.push(null);
				}
			}

			return data;
		},

		labels: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 100;
			var count = cfg.count || 8;
			var step = (max - min) / count;
			var decimals = cfg.decimals || 8;
			var dfactor = Math.pow(10, decimals) || 0;
			var prefix = cfg.prefix || '';
			var values = [];
			var i;

			for (i = min; i < max; i += step) {
				values.push(prefix + Math.round(dfactor * i) / dfactor);
			}

			return values;
		},

		months: function(config) {
			var cfg = config || {};
			var count = cfg.count || 12;
			var section = cfg.section;
			var values = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = Months[Math.ceil(i) % 12];
				values.push(value.substring(0, section));
			}

			return values;
		},

		color: function(index) {
			return COLORS[index % COLORS.length];
		},

		transparentize: function(color, opacity) {
			var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			return Color(color).alpha(alpha).rgbString();
		}
	};

	// DEPRECATED
	window.randomScalingFactor = function() {
		return Math.round(Samples.utils.rand(0, 100));
	};

	// INITIALIZATION

	Samples.utils.srand(Date.now());

	// Google Analytics
	/* eslint-disable */
	if (document.location.hostname.match(/^(www\.)?chartjs\.org$/)) {
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-28909194-3', 'auto');
		ga('send', 'pageview');
	}
	/* eslint-enable */

}(this));




(function($){

	$(document).ready(function(){


		const MONTHS = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sept", "Oct", "Nov", "Dic"];
		     var config = {
		         type: 'line',
		         data: {
		             labels: ["May", "Jun", "Jul", "Ago", "Sept", "Oct", "Nov"],
		             datasets: [{
		                 label: "Boletas",
		                 backgroundColor: window.chartColors.red,
		                 borderColor: window.chartColors.red,
		                 data: [
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor()
		                 ],
		                 fill: false,
		             }, {
		                 label: "Facturas",
		                 fill: false,
		                 backgroundColor: window.chartColors.blue,
		                 borderColor: window.chartColors.blue,
		                 data: [
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor(),
		                     randomScalingFactor()
		                 ],
		             }]
		         },
		         options: {
		             responsive: true,
		             title:{
		                 display:true,
		                 text:'Comprobantes emitidos'
		             },
		             layout: {
		                        padding: {
		                            left: 0,
		                            right: 0,
		                            top: 0,
		                            bottom: 0
		                        }
		             },
		             tooltips: {
		                 mode: 'index',
		                 intersect: false,
		             },
		             hover: {
		                 mode: 'nearest',
		                 intersect: true
		             },
		             scales: {
		                 xAxes: [{
		                     display: true,
		                     scaleLabel: {
		                         display: false,
		                         labelString: 'Month'
		                     }
		                 }],
		                 yAxes: [{
		                     display: true,
		                     scaleLabel: {
		                         display: false,
		                         labelString: 'Value'
		                     }
		                 }]
		             }
		         }
		     };


		     if(document.getElementById("myCanvasEl") != null){


			     const ctx = document.getElementById("myCanvasEl").getContext("2d");
			     window.myLine = new Chart(ctx, config);

		     }


		     let rqEnviarFactura = null;

		     $(document).on('click', '.btn_factura_estado', function(e){

		     	/*
					<i class="fa fa-check"></i> <span> Enviado </span>
					<i class="fa fa-times"></i> <span> Reenviar ('.$item['sunat_envios_fallidos'].') </span>
					<i class="fa fa-paper-plane"></i>   <span> Pendiente </span>
		     	*/

		     	e.preventDefault();

		     	$this = $(this);

				let params = {
			 		'action' : 'vex_fe_enviar_factura',
			 		'comprobante_id' : $this.data('comprobante-id')
				}

				// params = jQuery.extend({}, qParams, params);


		     	if(rqEnviarFactura != null && rqEnviarFactura.xhr != 4){
		     		rqEnviarFactura.abort();
		     	}

 				rqEnviarFactura = $.ajax({
 			        method: 'POST',
 			        url: vexfe_vars.url + '/wp-admin/admin-ajax.php',
 			        dataType: 'json',
 			        cache: false,
					'data' : params,
					beforeSend: function(){
						$this.html('Enviando..');
					},

 			        success: function(data) {

 			        	console.log(data);

 			        	if( data.result == '1' ) {

 			        		$this.html( '<i class="fa fa-check"></i> <span> Enviado </span>' );

 			        	}
 			        	else if(data.result == '0')
 			        	{
 			        		$this.html( `<i class="fa fa-times"></i> <span> Reenviar (${data.intentos_fallidos}) </span>` );
 			        	}
 			        	else
 			        	{
 			        		$this.html('<i class="fa fa-paper-plane"></i> <span> Pendiente </span>');
 			        	}

 			        },
 			        error: function(jqXHR, textStatus, errorThrown) {

 			        	console.log('ERROR');
 			        	console.log(jqXHR);
 			        	console.log(textStatus);
 			        }
 			    });


		     	//alert('Ola que hace');

		     });



		     $(document).on('click', '.btn_factura_editar', function(e){

		     		$(this).html('Enviando..');
		     });



		     $(document).on('click', '.btn_factura_anular', function(e){

		     		$(this).html('Enviando..');
		     });



		     let rqConsultaResumenDiario = null;



		     $('#btnGenerarResumenDiario').click(function(e){

		     	e.preventDefault();

		     	if(rqConsultaResumenDiario != null && rqConsultaResumenDiario.xhr != 4){
		     		rqConsultaResumenDiario.abort();
		     	}

		     	let fecha = $('input[name="resumen_diario_fecha"]')[0].value;

 				rqConsultaResumenDiario = $.ajax({
 			        method: 'POST',
 			        url: vexfe_vars.url + '/wp-admin/admin-ajax.php',
 			        dataType: 'html',
 			        cache: false,
					'data' : {'action' :  'vex_fe_consulta_boletas_resumen_diario',
							  'fecha' : fecha },

					beforeSend: function(){

						$('#vexfeResultadoVisualizarComprobante').html('Cargando..');

					},

 			        success: function(data) {

						$('#vexfeResultadoVisualizarComprobante').html(data);

 			        },
 			        error: function(jqXHR, textStatus, errorThrown) {

 			        	console.log('ERROR');
 			        	console.log(jqXHR);
 			        	console.log(textStatus);
 			        }
 			    });


		     });


		     $('#btnEnvioResumenDiario').click(function(e){

		     	e.preventDefault();


		     	let fecha = $('input[name="resumen_diario_fecha"]')[0].value;
		     	let boleta_ini = $('input[name="resumen_boleta_inicio"]')[0].value;
		     	let boleta_fin = $('input[name="resumen_boleta_fin"]')[0].value;


		     	if(rqConsultaResumenDiario != null && rqConsultaResumenDiario.xhr != 4){
		     		rqConsultaResumenDiario.abort();
		     	}

 				rqConsultaResumenDiario = $.ajax({
 			        method: 'POST',
 			        url: vexfe_vars.url + '/wp-admin/admin-ajax.php',
 			        dataType: 'html',
 			        cache: false,
					'data' : {'action' :  'vex_fe_consulta_boletas_resumen_diario',
							  'fecha' : fecha },

					beforeSend: function(){

						$('#vexfeResultadoVisualizarComprobante').html('Cargando..');

					},

 			        success: function(data) {

						$('#vexfeResultadoVisualizarComprobante').html(data);

 			        },
 			        error: function(jqXHR, textStatus, errorThrown) {

 			        	console.log('ERROR');
 			        	console.log(jqXHR);
 			        	console.log(textStatus);
 			        }
 			    });


		     });


		// jQuery('.btn_estado_pendiente')


	});



})(jQuery);
