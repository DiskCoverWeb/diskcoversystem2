

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
  			'orden':$('#ddl_orden').val(),
  			'mes':$('#ddl_meses').val(),
  			'semanas':$('#ddl_semanas').val(),
  			'fecha':$('#txt_fecha').val(),
  		}
  		$.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/reporte_constructora_Compras.php?cargar_datos_tiempos=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) {
        	console.log(response);
        	var table = '';
        	var total = 0;
        	response.forEach(function(item,i){
        		table+=`<tr>
        		<td>`+item.Orden_No+`</td>
        		<td>`+item.solicitud+`</td>
        		<td>`+item.aprobacion+`</td>
        		<td>`+item.dias1+`</td>
        		<td>`+item.proveedor+`</td>
        		<td>`+item.dias2+`</td>
        		<td>`+item.compra+`</td>
        		<td>`+item.dias3+`</td>
        		</tr>`
        		// total= total+parseFloat(item.total);
        	})

        	$('#lbl_num_ord').text(response.length);
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
  		$('#ddl_orden').select2({
	      placeholder: 'Seleccione orden',
	      ajax: {
	        url:   '../controlador/inventario/reporte_constructora_Compras.php?ddl_orden=true',
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
  		orden();
  	}
  	function limpiar_orden()
  	{
  		$('#ddl_orden').empty();
  		cargar_datos();
  	}

  	function imprimir_pdf()
  	{  		
  		var url = '../controlador/inventario/reporte_constructora_Compras.php?cargar_tiempos_pdf=true&orden='+$('#ddl_orden').val()+'&mes='+$('#ddl_meses').val()+'&semanas='+$('#ddl_semanas').val()+'&fecha='+$('#txt_fecha').val()                 
      window.open(url, '_blank');
  	}
  	function imprimir_excel()
  	{  		
  		var url = '../controlador/inventario/reporte_constructora_Compras.php?cargar_tiempos_excel=true&orden='+$('#ddl_orden').val()+'&mes='+$('#ddl_meses').val()+'&semanas='+$('#ddl_semanas').val()+'&fecha='+$('#txt_fecha').val()                 
      window.open(url, '_blank');
  	}