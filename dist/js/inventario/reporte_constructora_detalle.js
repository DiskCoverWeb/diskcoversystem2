	function obtenerSemanasDelMes(year, month) {
    // Ajustar month (0-11 en JavaScript)
	    const firstDay = new Date(year, month - 1, 1);
	    const lastDay = new Date(year, month, 0); // Último día del mes
	    
	    // Función para número de semana ISO
	    const getISOWeek = (date) => {
	        const d = new Date(date);
	        d.setHours(0, 0, 0, 0);
	        d.setDate(d.getDate() + 3 - (d.getDay() + 6) % 7);
	        const firstDayOfYear = new Date(d.getFullYear(), 0, 1);
	        const diffDays = Math.round((d - firstDayOfYear) / (86400000));
	        return Math.floor(diffDays / 7) + 1;
	    };

	    // Calcular semanas del primer y último día
	    const firstWeek = getISOWeek(firstDay);
	    const lastWeek = getISOWeek(lastDay);

	    // Generar array de semanas
	    const semanas = [];
	    for (let week = firstWeek; week <= lastWeek; week++) {
	        semanas.push(week);
	    }

	    return semanas;
	}

  	function NumeroSemanasxAnio(year) {
	    const firstDayOfYear = new Date(year, 0, 1);
	    const dayOfWeek = firstDayOfYear.getDay();
	    // Verificar si el año es bisiesto
	    const isLeapYear = (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
	    if (dayOfWeek === 4 || (isLeapYear && dayOfWeek === 3)) {
	        return 53;
	    } else {
	        return 52;
	    }
	}

  	function meses()
  	{
  		
  		response = [
  			{'num':'01','mes':'Enero'},
  			{'num':'02','mes':'Febrero'},
  			{'num':'03','mes':'Marzo'},
  			{'num':'04','mes':'Abril'},
  			{'num':'05','mes':'Mayo'},
  			{'num':'06','mes':'Junio'},
  			{'num':'07','mes':'Julio'},
  			{'num':'08','mes':'Agosto'},
  			{'num':'09','mes':'Septiembre'},
  			{'num':'10','mes':'Octubre'},
  			{'num':'11','mes':'Noviembre'},
  			{'num':'12','mes':'diciembre'},
  		]
       		response.forEach(function(item,i)
       		{
       			$('#ddl_meses').append('<option value="'+item.num+'">'+item.mes+'</option>');
       		})
     
  	}

  	function Calcularsemanas()
  	{
  		const year = new Date().getFullYear();
  		$('#ddl_semanas').html('<option value="">Seleccione</option>');
  		if($('#ddl_meses').val()=='')
  		{
  			semanas = NumeroSemanasxAnio(year)
  			for (var i = 1; i <= semanas; i++) {
	  			$('#ddl_semanas').append('<option value="'+i+'">'+i+'</option>')
	  		}
  		}else
  		{
  			var month = $('#ddl_meses').val();
  		 	semanas = obtenerSemanasDelMes(year, month);
  		 	semanas.forEach(function(item,i){
  		 			$('#ddl_semanas').append('<option value="'+item+'">'+item+'</option>')
  		 	})
  		}
  	}

  	function cargar_datos()
  	{

  		 if ($.fn.DataTable.isDataTable('#tbl_data')) {
			      $('#tbl_data').DataTable().destroy();
			  }

  		var parametros = 
  		{
  			'contratista':$('#ddl_contratista').val(),
  			'orden':$('#ddl_orden').val(),
  			'mes':$('#ddl_meses').val(),
  			'semanas':$('#ddl_semanas').val(),
  			'fecha':$('#txt_fecha').val(),
  			'hasta':$('#txt_fecha_hasta').val(),
  		}
  		$.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/reporte_constructora_Compras.php?cargar_datos=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) {
        	table = '';
        	total_global = 0;
        response.forEach(function(item,i){
        	compra = parseFloat(item.valor_compra);
        	refere = parseFloat(item.valor_ref);
        	ahorro = refere-compra;
        	color = 'chartreuse';
        	if(ahorro<0)
        	{
        		color = 'coral';
        	}

        	table+=`<tr>
			        			<td><a href="#" onclick='abrir_detalle("`+item.Orden_No+`","`+item.Codigo_P+`")'>`+item.Orden_No+`</a></td>
			        			<td>`+item.Cliente+`</td>
			        			<td>`+item.Fecha.date+`</td>
			        			<td>`+refere.toFixed(3)+`</td>
			        			<td>`+compra.toFixed(3)+`</td>
			        			<td style="background: `+color+`;">`+ahorro.toFixed(3)+`</td>
			        	</tr>`
			     total_global = total_global+ahorro;
        })   

           table+=`<tr>
           						<td></td>
           						<td></td>
           						<td></td>
           						<td></td>
           						<td><b>TOTAL</b></td>
           						<td>`+total_global.toFixed(4)+`</td>
           					</tr>`     	
        	$('#tbl_body').html(table)


          $('#tbl_data').DataTable({
              scrollX: true,
              scrollCollapse: true, 
              searching: false,
              responsive: false,
              paging: true,   
              info: false,   
              autoWidth: false,   
          		// order: [[1, 'asc']], // Ordenar por la segunda columna
              /*autoWidth: false,
              responsive: true,*/
              language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            // columnDefs: [
            //     { targets: 2, width: "200px" },
            //     { targets: 3, width: "500px" },
            // ],
          });


  		}
      });
  	}

  	function contratistas()
  	{
  		$('#ddl_contratista').select2({
	      placeholder: 'Seleccione contratista',
	      width:'resolve',
	      ajax: {
	        url:   '../controlador/inventario/reporte_constructora_Compras.php?contratistas=true',
	        dataType: 'json',
	        delay: 250,
	        processResults: function (data) {
	          return {
	            results: data
	          };
	        },
	        cache: true
	      }
	    });

  	}

  	function orden()
  	{
  		let contra = $('#ddl_contratista').val() || ''
  		$('#ddl_orden').select2({
	      placeholder: 'Seleccione orden',
	      ajax: {
	        url:   '../controlador/inventario/reporte_constructora_Compras.php?ddl_orden=true&contratista='+contra,
	        dataType: 'json',
	        delay: 250,
	        processResults: function (data) {
	          return {
	            results: data
	          };
	        },
	        cache: true
	      }
	    });

  	}

  	function limpiar_contra()
  	{
  		$('#ddl_contratista').empty()
  		$('#ddl_contratista').val('')
  		orden();
  	}
  	function limpiar_orden()
  	{
  		$('#ddl_orden').empty();
  		cargar_datos();
  	}

  	function abrir_detalle(orden,proveedor)
  	{
  		cargar_detalle(orden,proveedor)
  		$('#myModal_detalle').modal('show');
  	}

  	function cargar_detalle(orden,proveedor)
  	{
  		 if ($.fn.DataTable.isDataTable('#tbl_detalle')) {
			      $('#tbl_detalle').DataTable().destroy();
			  }

  		var parametros = 
  		{
  			'proveedor':proveedor,
  			'orden':orden,
  		}
  		$.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/reporte_constructora_Compras.php?cargar_detalles=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) {
        	console.log(response);
        	tr='';
        	ahorro_t = 0;
        	ref_t = 0;
        	compra_t = 0;

        	response.forEach(function(item,i){
        		compra = parseFloat(item.Valor_Total);
	        	refere = parseFloat(item.Costo)*parseFloat(item.Entrada);
	        	ahorro = refere-compra;
	        	color = 'chartreuse';
	        	if(ahorro<0)
	        	{
	        		color = 'coral';
	        	}

        	 tr+=`<tr>
	        	 			<td>`+(i+1)+`</td>
	      					<td>`+item.familia+`</td>
	      					<td>`+item.Codigo_Inv+`</td>
	      					<td>`+item.Producto+`</td>
	      					<td>`+item.Marca+`</td>
	      					<td>`+item.Entrada+`</td>
	      					<td>`+item.Costo+`</td>
	      					<td>`+refere+`</td>
	      					<td>`+item.Valor_Unitario+`</td>
	      					<td>`+item.Valor_Total+`</td>	      					
	      					<td style="background:`+color+`" >`+ahorro.toFixed(3)+`</td>
	      				</tr>`;
	      				ahorro_t = ahorro_t+ahorro
	      				ref_t = ref_t+refere
								compra_t = compra_t+compra
        	})

        	color = 'chartreuse';
        	if(ahorro_t<0)
        	{
        		color = 'coral';
        	}

        	$('#lbl_comprobante').text(response[0].Numero);
					$('#lbl_referencial').text(ref_t.toFixed(3));
					$('#lbl_compra').text(compra_t.toFixed(3));
					$('#lbl_ahorro').text(ahorro_t.toFixed(3));					
					$('#lbl_ahorro').css('background',color);
					$('#lbl_proveedor').text(response[0].Cliente);       

        	$('#tbl_detalle_body').html(tr)

        	 $('#tbl_detalle').DataTable({
              scrollX: true,
              scrollCollapse: true, 
              searching: false,
              responsive: false,
              paging: false,   
              info: false,   
              autoWidth: false,   
          		// order: [[1, 'asc']], // Ordenar por la segunda columna
              /*autoWidth: false,
              responsive: true,*/
              language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            // columnDefs: [
            //     { targets: 2, width: "200px" },
            //     { targets: 3, width: "500px" },
            // ],
          });

       

  			}
      });
  	}


  	function imprimir_pdf()
  	{  		
  		var url = '../controlador/inventario/reporte_constructora_Compras.php?cargar_detalles_pdf=true&contratista='+$('#ddl_contratista').val()+'&orden='+$('#ddl_orden').val()+'&mes='+$('#ddl_meses').val()+'&semanas='+$('#ddl_semanas').val()+'&fecha='+$('#txt_fecha').val()+'&hasta='+$('#txt_fecha_hasta').val()                                  
      window.open(url, '_blank');
  	}
  	function imprimir_excel()
  	{  		
  		var url = '../controlador/inventario/reporte_constructora_Compras.php?cargar_detalles_excel=true&contratista='+$('#ddl_contratista').val()+'&orden='+$('#ddl_orden').val()+'&mes='+$('#ddl_meses').val()+'&semanas='+$('#ddl_semanas').val()+'&fecha='+$('#txt_fecha').val()+'&hasta='+$('#txt_fecha_hasta').val()                 
      window.open(url, '_blank');
  	}