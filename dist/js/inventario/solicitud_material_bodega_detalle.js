	
function pedidos_contratista(orden)
{     
  var parametros = 
  {
    'order': orden,
  }
  $.ajax({
      url:   '../controlador/inventario/solicitud_material_bodegaC.php?pedidos_contratista_detalle=true',
      type:  'post',
      data: {parametros:parametros},
      dataType: 'json',
      success:  function (response) {           
        $('#tbl_body').html(response.tabla);       
        $('#lbl_contratista').text(response.datos[0]['Cliente']);
				$('#lbl_orden').text(response.datos[0]['Orden_No']); 
				if(response.estado=='.')
				{
					$('#btn_aprobar').css('display','block');
				}else
				{
					$('#btn_comprobante').css('display','block');
				}             
      }
  });
}

function cargar_rubro_linea(id,cc)
{
	var parametros = 
	  {
	    'cc':cc,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?listar_rubro=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
	        op = '<option value="">Seleccione rubro</option>';           
	        response.forEach(function(item,i){
	        	op+='<option value="'+item.id+'">'+item.text+'</option>'
	        })       

	        $('#ddl_linea_rubro_'+id).html(op);
	      }
	  });

}

function guardar_linea(id)
{
	if($('#ddl_linea_cc_'+id).val()=='' || $('#ddl_linea_rubro_'+id).val()=='')
	{
		Swal.fire("","Seleccione todos los capos","info")
		return false;
	}
	if($('#txt_salida_'+id).val()=='' || $('#txt_salida_'+id).val()=='0')
	{
		Swal.fire("","Cantidad incorrecta","info")
		return false;
	}
	var parametros = 
	  {
	    'cc':$('#ddl_linea_cc_'+id).val(),
	    'rubro':$('#ddl_linea_rubro_'+id).val(),
	    'salida':$('#txt_salida_'+id).val(),
	    'ID':id,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?editarCCRubro=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
	      	if(response==1)
	      	{
	      		Swal.fire('','Linea Editada','success').then(function(){
	      			pedidos_contratista(orden);	 
	      		})   
	      	}     
	      }
	  });

}

function eliminar_linea(id)
{
	 Swal.fire({
       title: 'Esta seguro?',
       text: "Esta usted seguro de que quiere borrar este registro!",
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Si!'
     }).then((result) => {
       if (result.value==true) {
        linea_eliminar(id);
       }
     })
}

function linea_eliminar(id)
{

	 $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?eliminarLinea=true',
	      type:  'post',
	      data: {id:id},
	      dataType: 'json',
	      success:  function (response) {
	      	if(response==1)
	      	{
	      		Swal.fire('','Linea eliminada','success').then(function(){
	      			pedidos_contratista(orden);	 
	      		})   
	      	}     
	      }
	  });

}

function AprobarSolicitud()
{
	var parametros = 
	  {
	    'order': orden,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?AprobarSolicitud=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {           
	        if(response==1)
	        {
	        	Swal.fire("","Solicitud Aprobada","success").then(function(){
	        		location.reload();
	        	});
	        }   
	      }
	  });
}
function GenerarComprobante()
{
	$('#myModal_espera').modal('show');
	var parametros = 
	  {
	    'order': orden,
	    'T_No':'102',
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?GenerarComprobante=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
					$('#myModal_espera').modal('hide');           
	        if(response.resp==1)
	        {
	        	Swal.fire("Comprobate "+response.com+" Generado:","","success").then(function(){
	        		window.open('../controlador/contabilidad/comproC.php?reporte&comprobante='+response.com+'&TP=CD','_blank')
	        		location.reload();
	        	});
	        }   
	      }
	  });

}