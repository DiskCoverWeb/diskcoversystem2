  $( document ).ready(function() {
     provincia();
     // cargar_clientes();
     console.log()

      tbl_cliente_all = $('#tbl_pacientes').DataTable({
          scrollX: true,
          searching: false,
          responsive: false,
          // paging: false,   
          info: false,   
          autoWidth: false,   
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
          url:   '../controlador/farmacia/pacienteC.php?pacientes=true',
          type: 'POST',  // Cambia el método a POST   
          data: function(d) {
              var query = $('#txt_query').val();
              var rbl = $('input:radio[name=rbl_buscar]:checked').val();
              var pag =$('#txt_pag').val();
              var parametros = 
              {
                'query':query,
                'tipo':rbl,
                'codigo':'',
              }
              return { parametros: parametros };
          },   
          dataSrc: '',             
        },
         scrollX: true,  // Habilitar desplazamiento horizontal
     
        columns: [
          { data:'ID'},
          { data:'Codigo'},
          { data:'Cliente'},
          { data:'CI_RUC'},
          { data:'Telefono'},
          { data:  null,
            render: function(data, type, item) {
              return `<a href="../vista/inicio.php?mod=`+ModuloActual+`&acc=vis_descargos&cod=${item.Matricula}&ci=${item.CI_RUC}" class="btn btn-outline-secondary btn-sm" title="Ver Historial"><span class="bx bx-grid-alt"></span></a>
              <button class="btn btn-sm btn-primary" onclick="buscar_cod('E','${item.CI_RUC}')" title="Editar paciente"><span class="bx bx-pencil"></span></button>             
              <button class="btn btn-sm btn-danger" title="Eliminar paciente"  onclick="eliminar('${item.ID}','${item.CI_RUC}')" ><span class="bx bx-trash"></span></button>`;   
                           
            }

          },
         
          
        ],
      });

  });

  function provincia()
  {
     $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?provincias=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response)
        {
          var op = '<option value="">Seleccione provincia</option>';
          $.each(response,function(i,item){
             op+= '<option value="'+item.Codigo+'">'+item.Descripcion_Rubro+'</option>';
          });
          $('#ddl_provincia').html(op);
          $("#ddl_provincia").val('17');
        }
      }
    });
  }
  function nombres(nombre)
  {
    $('#txt_nombre').val(nombre.ucwords());
  }

  function cargar_clientes()
  {

     tbl_cliente_all.ajax.reload(null, false);
  }

  function limpiar()
  {

    $('#txt_codigo').val('');
    $('#txt_nombre').val('');
    $('#txt_ruc').val('');
    // $('#ddl_provincia').val('');
    // $('#txt_localidad').val('');
    $('#txt_telefono').val('');
    $('#txt_tip').val('N');    
    $('#txt_id').val('');
    // $('#txt_email').val('');
    $('#btn_nu').html('<i class="fa fa-plus"></i> Nuevo cliente');
    // $('#txt_codigo').attr("readonly", false);
  }


  function nuevo_paciente()
  {
    if($('#txt_validado').val()==0)
    {
      // Swal.fire('Se esta validando la cedua','','info');
      return false;
    }
    var parametros = 
    {
       'cod':$('#txt_codigo').val(),
       'id':$('#txt_id').val(),
       'nom':$('#txt_nombre').val(),
       'ruc':$('#txt_ruc').val(),
       'pro':$('#ddl_provincia').val(),
       'loc':$('#txt_localidad').val(),
       'tel':$('#txt_telefono').val(),
       'ema':$('#txt_email').val(),
       'tip':$('#txt_tip').val(),
    }
    if($('#txt_codigo').val() =='' || $('#txt_nombre').val()=='' || $('#txt_ruc').val()=='' || $('#ddl_provincia').val()=='' || $('#txt_localidad').val()=='' || $('#txt_telefono').val()== '' || $('#txt_email').val()=='' || $('#txt_tip').val()=='')
    {

       Swal.fire('','Llene todo los campos.','info');
      return false;
    }
    if($('#txt_codigo').val() =='' || $('#txt_codigo').val()==0)
    {
       Swal.fire('Numero de Historia invalido.','','info');
      return false;
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          if(parametros.tip=='E')
          {
            cargar_clientes();
           limpiar();
           Swal.fire('Cliente Editado.','','success');
           
           }else
           {
            cargar_clientes();
            limpiar();
            Swal.fire('Nuevo Cliente Registrado.','','success');
            
           }          
        }else if(response==-2)
        {
           Swal.fire('Cedula incorrecta','','error');
        }else if(response==-3){
           Swal.fire('Nombre Regisrado','','info');
        }else
        {
          Swal.fire('','Existio algun tipo de problema intente mas tarde.','error');
        }
      }
    });

  }

  function buscar_cod(tipo,campo)
  {

    // $('#txt_codigo').attr("readonly",true);
    $('#myModal_espera').modal('show');    
    $('#btn_nu').html('<i class="fa fa-pencil"></i> Editar cliente');
    $('#txt_tip').val('E');
    var query = $('#'+campo).val();
    var parametros;
    if(tipo=='N' || tipo=='N1')
    {
     parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }else if(tipo=='R' || tipo=='R1' )
    {
       parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }else if(tipo=='E')
    {
      parametros = 
      { 
        'query':'',
        'tipo':'',
        'codigo':campo,
      }

    }else
    {
      parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }
    
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response !=-1)
        {
           $('#txt_codigo').val(response.matricula);
           $('#txt_nombre').val(response.nombre);
           $('#txt_ruc').val(response.ci);
           $('#ddl_provincia').val(response.prov);
           $('#txt_localidad').val(response.localidad);
           $('#txt_telefono').val(response.telefono);
           $('#txt_email').val(response.email);
           $('#txt_id').val(response.id);
           $('#txt_validado').val(1);
           if(response.matricula == 0 || response.matricula == '')
           {
                $('#txt_codigo').attr("readonly",false);
           }
           $(window).scrollTop(0);
          
        }else
        {
          var query = $('#'+campo).val();
          limpiar();
          $('#'+campo).val(query);
          Swal.fire('','No se a es encontrado registros.','info');
        }

        setTimeout(() => { $('#myModal_espera').modal('hide');  }, 1000);

        
      },error: function (error) {
        $('#myModal_espera').modal('hide');    
      }
    });
  }

  function eliminar(cli,ruc)
  {
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
              $.ajax({
                data:  {cli:cli,ruc:ruc},
                url:   '../controlador/farmacia/pacienteC.php?eliminar=true',
                type:  'post',
                dataType: 'json',
                success:  function (response) 
                      {
                        if(response ==1)
                          {
                            Swal.fire('','Registro eliminado.','success');
                            cargar_clientes();
                          }else
                          {
                            Swal.fire('','Este usuario tiene Datos ligados.','error');
                          }
                      }
                });
        }
      });

  }

  function validar_num_historia()
  {
    var num = $('#txt_codigo').val();
    if(!num=='')
    {
       parametros = 
      { 
        'query':num,
        'tipo':'C1',
        'codigo':'',
      }
       $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?historial_existente=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response==-1)
        {
          Swal.fire('','El numero de Historia ya existe.','error');
          $('#txt_codigo').val('');
        }
      }
    });

    }
  }

  function validar_ci()
  {
     var num = $('#txt_ruc').val();
    
      $.ajax({
      data:  {num:num},
      url:   '../controlador/farmacia/pacienteC.php?validar_ci=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response.Tipo_Beneficiario == 'P')
        {
           // Swal.fire('Numero de cedula invalido.','','error');
           Swal.fire('Advertencia este no es un numero de cedula.','','info');
            // $('#txt_ruc').val('');
           // return false;
        }
      }
    });
  }

  function paciente_existente()
  {
    var num = $('#txt_ruc').val();
    // if(num.length<10)
    // {
    //   Swal.fire('La cedula no tiene 10 caracteres','','info');
    //   return false;
    // }

    if(!num=='')
    {
      validar_ci();
       parametros = 
      { 
        'query':num,
        'tipo':'R1',
        'codigo':'',
      }
       $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?paciente_existente=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response!=-1)
        {
          Swal.fire({
            title: 'Esta Cedula ya esta registrada! <br> Pero podria no tener Numero de historia clinica <br> Desea cargar sus datos!',
            // text: "Desea cargar sus datos!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
            }).then((result) => {
              if (result.value) {
                $('#txt_codigo').val(response.matricula);
                $('#txt_nombre').val(response.nombre);
                $('#txt_ruc').val(response.ci);
                $('#ddl_provincia').val(response.prov);
                $('#txt_localidad').val(response.localidad);
                $('#txt_telefono').val(response.telefono);
                $('#txt_email').val(response.email);
                $('#txt_id').val(response.id);
                $('#txt_tip').val('E');
                if(response.matricula == 0 || response.matricula == '')
                {
                     $('#txt_codigo').attr("readonly",false);
                }

                }else
                {
                limpiar();
                $('#txt_ruc').val('');
                $('#txt_validado').val(0);
                }
            });
        }else
        {
           $('#txt_id').val('');
           $('#txt_codigo').val('');
           $('#txt_tip').val('N');
        }
        $('#txt_validado').val(1);
      }
    });

    }
  }