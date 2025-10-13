<?php  $num_ped = '';$cod=''; $area = ''; $pro=''; if(isset($_GET['num_ped'])){$num_ped =$_GET['num_ped'];} if(isset($_GET['cod'])){$cod =$_GET['cod'];} if(isset($_GET['area'])){$area1 = explode('-', $_GET['area']); $area =$area1[0];$pro=$area1[1]; } $_SESSION['INGRESO']['modulo_']='99'; date_default_timezone_set('America/Guayaquil'); 
      unset($_SESSION['NEGATIVOS']['CODIGO_INV']);?>
<script type="text/javascript">
    var c = '<?php echo $cod; ?>';
    var area = '<?php echo $area; ?>';
    var pro = '<?php echo $pro; ?>';
    var cod = '<?php echo $cod; ?>';
    var num_ped = '<?php echo $num_ped; ?>';
</script>
<script src="../../dist/js/farmacia/ingreso_descargos.js"></script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>

<div class="row row-cols-auto  mb-2">
  <div class="btn-group">
    <a  href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo"  class="btn btn-outline-secondary btn-sm">
      <img src="../../img/png/salire.png">
    </a>
    <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>&acc=pacientes" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_pdf" title="Pacientes">
      <img src="../../img/png/pacientes.png">
    </a>
    <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>&acc=vis_descargos" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_excel" title="Descargos">
      <img src="../../img/png/descargos.png">
    </a>        
    <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>&acc=articulos" title="Ingresar Articulos"  class="btn btn-outline-secondary btn-sm" onclick="">
      <img src="../../img/png/articulos.png" >
    </a>
    <button title="Mayorizar Articulos"  class="btn btn-outline-secondary btn-sm" onclick="mayorizar_inventario()">
      <img src="../../img/png/update.png" >
    </button>
    <button title="Mayorizar Articulos"  class="btn btn-outline-secondary btn-sm" onclick="enviaremail()">
      <img src="../../img/png/update.png" >
    </button>

  </div>
</div>


<script>
  function enviaremail()   //funcion para enviarlo por javascript
  { 


          const xhr = new XMLHttpRequest();
          // const url =  'https://erp.diskcoversystem.com/~diskcover/lib/phpmailer/EnvioEmailvisual.php?EnviarVisual';
          const url =  'https://erp.diskcoversystem.com/lib/phpmailer/EnvioEmailvisual.php?EnviarVisual';
          // const url =  '../../lib/phpmailer/EnvioEmailvisual.php?EnviarVisual';

          xhr.open('POST', url, true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              console.log('Respuesta:', xhr.responseText);
            }
          };

           // const params = `from=admin@imap.diskcoversystem.com
           //                 &fromName=CORREO DESDE 192.168.20.3 RELAYHOST IMAP <admin@imap.diskcoversystem.com>
           //                 &to=javier.farinango92@gmail.com;diskcoversystem@msn.com
           //                 &body=juan@ejemplo.com
           //                 &subject=hola email como estas
           //                 &HTML=1
           //                 &Archivo=
           //                 &reply=
           //                 &replyName=
           //                 &debug=0
           //                 &item=001
           //                 &modulo=01
           //                 &CodigoU=1722214507 
           //                 &RUCEmpresa=1722214507001         
           //                 &Nombre=javier farinango         
           //                 &Mail_de=javier farinango        
           //                 &Mail_para=javier farinango        
           //                 &Proceso=proceso        
           //                 &Tarea=tarea 11        
           //                 &Credito_No= 11111`;


          const params = `from=informacion@imap.diskcoversystem.com&fromName=Actualizacion de DiskCover System <informacion@imap.diskcoversystem.com>
          &to=actualizar@diskcoversystem.com;diskcoversystem@msn.com;diskcover.system@yahoo.com;diskcover.system@gmail.com
          &subject=Prueba de Mails por imap.diskcoversystem.com desde UPDATE [ESO99], Hora (14:45:24), IP: 192.168.27.55
          &HTML=1
          &reply=
          &replyName=
          &debug=0
          &Archivo=
          &item=000
          &modulo=UPDATE
          &CodigoU=ACCESO99
          &RUCEmpresa=9999999999999
          &Nombre=Update DiskCover
          &Mail_de=informacion@imap.diskcoversystem.com
          &Mail_para=actualizar@diskcoversystem.com;diskcoversystem@msn.com;diskcover.system@yahoo.com;diskcover.system@gmail.com
          &Proceso=Email de: informacion@imap.diskcoversystem.com
          &Tarea=Asunto: Prueba de Mails por imap.diskcoversystem.com desde UPDATE [ESO99], Hora (14:45:24), IP: 192.168.27.55&Credito_No=&body=<!DOCTYPE html>
<html>
<head>
  <title>Cartera Clientes</title>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel="shortcut icon" href="https://www.diskcoversystem.com/dsimg/diskcover_system.jpg" />  
  <style rel='stylesheet' type='text/css' media='screen'>
    body {
      margin: 15px;
      font-family: monospace;
    }
    header{
      padding: 1px;     
      text-align: center;
    }
    h2 {
      text-shadow: 0 0 3px white;
      font-weight: bold;
    }
    #izq{
      text-align: left;
    }
    #der{
      text-align: right;
    }
    #central{
      text-align: center;
      font-weight: bold;
    }   
    #contenido {
      display: flex;
      flex-direction: column;
    }
    #destacados {
      background-color: #669999;
    }
    #ofertas {
      background-color: #F7D988;
    }
    #disponible {
      background-color: #F7A188;
    }
    #contenido > section > h1 {
      text-align: center;
    }
    footer {
      margin-top: 5px;
      border-top: solid 1px black;
      padding-top: 5px;
      text-align: center;
    }
    .contenedor {
      display: flex;
      flex-direction: row;
      width: 100%;
      max-width: 1366px;
      margin: auto;
    }
    #destacados .contenedor > div {
      /*text-align: center;*/
      padding: 10px;
      margin: 5px;
      background-color: white;
      width: 50%;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(255, 255, 0, 0.3);
    }
    #destacados h1 {
      text-shadow: 0 0 3px white;
    }
    #ofertas .contenedor > div {
      padding: 10px;
      margin: 5px;
      background-color: white;
      width: 33.3%;
      box-shadow: 4px 8px;
    }
    #ofertas h1 {
      text-shadow: 1px 1px 12px white, 0 0 30px #D46A6A;
    }
    #disponible .contenedor > div {
      padding: 10px;
      background-color: white;
      width: 24%;
      margin: 5px;
      box-shadow: 4px 8px #D46A6A;
    }
    img.icono {
      border: double #A52A2A 3px; 
      border-radius: 13em/3em;
      width: 60px;
      margin:1px;
      }
    .table{
      width: 100%;
      border: 1px solid #ccc;
      border-collapse: collapse;
      margin: 10px;
      padding: 0px;
      table-layout: fixed;
    }
    .table caption{
      font-size: 20px;
      text-transform: uppercasex;
      font-weight: bold;
      margin: 8px 0px;
    }
    .table tr{
      background-color: #f8f8f8;
      border: 1px solid #ddd;
    }
    .table th, .table td {
      font-size: 16px;
      padding: 8px;
      /*text-align: center;*/
    }
    .table thead th{
      text-transform: uppercase;
      background-color: #ddd;
    }
    .table tbody tr:hover{
      background-color: rgba(0,0,0,0.2);
    }
    .table tbody td:hover{
      background-color: rgba(0,0,0,0.3);
    }
    .text-short{
      font-size: 10px;
    }
    .text-justificado{
      text-align: justify;
    }
    .row{
      overflow: hidden;
    }
    .margin-b-0{
      margin-bottom: 0px;
    }
        /*
         En las siguientes lineas se define el diseÃ±o adaptable, para que se muestre en los dispositivos moviles
        */
        /******************************************/
        /***    DISEÃ‘O PARA MOVILES 600        ****/
        /******************************************/    
    @media screen and (max-width: 600px) {
      .table{
        border: 0px;
      }
      .table caption {
        font-size: 14px;
      }
      .table thead{
        display: none;
      }
      .table tr{
        margin-bottom: 8px;
        margin-bottom: 4px solid #ddd;
        display: block;
      }
      .table th, .table td{
        font-size: 12px;
      }
      .table td{
        display: block;
        border-bottom: 1px solid #ddd;
        text-align: right;
      }
      .table td:last-child{
        border-bottom: 0px;
      }
      .table td::before{
        content: attr(data-label);
        font-weight: bold;
        text-transform: uppercase;
        float: left;
      }
    }
  </style>
</head>
<body>
  <header>
    <section id='ofertas'>
      <div class='contenedor' id='central'> 
        <table class='table'>
          <thead>
            <tr>
              <th><img src='https://erp.diskcoversystem.com/img/logotipos/DiskCover.gif' alt='plataforma' width='100px' height='50px'></th>
              <th><h1>DISKCOVER SYSTEM</h1></th>
            </tr>   
          </thead>      
        </table>
      </div>  
      <h2>ACTUALIZACION DE BASES<br>R.U.C. 9999999999999<br>Direccion: www.diskcoversystem.com<br>Telefono(s): 09-9965-4196/09-8910-5300</h2>
    </section>
  </header> 
  <section id='contenido'>
    <section id='destacados'>
      <div class='contenedor' id='central'>
        <table class='table'>
          <caption>Detalle del Srvidor FTP</caption>
          <thead>
            <tr>
              <th>Usuario</th>
              <th>Clave</th>
            </tr>   
          </thead>
          <tbody>
            <tr>
              <td data-label='Usuario'>DiskCover</td>
              <td data-label='Clave'>**********</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class='contenedor' id='central'>
        <table class='table'>
          <caption>Detalle del Telefono SIP</caption>
          <thead>
            <tr>
              <th>Usuario</th>
              <th>Clave</th>
              <th>IP Local</th>
            </tr>   
          </thead>
          <tbody>
            <tr>
              <td data-label='Usuario'>DiskCover System</td>
              <td data-label='Clave'>**********</td>
              <td data-label='Clave'>Localhost</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
    <section id='disponible'>
      <div class='contenedor'>
        <table class='table'>
          <caption>SERVIDORES PRINCIPALES</caption>
          <thead>
            <tr>
              <th>Red WIFI</th>
              <th>IP LAN</th>
              <th>Usuario</th>
              <th>Clave WIFI</th>
            </tr>   
          </thead>
          <tbody>
            <tr>
              <td data-label='Red WIFI'>DiskCover System FTP</td>
              <td data-label='IP LAN'>192.168.27.55</td>
              <td data-label='Usuario'>DiskCoverFtp</td>
              <td data-label='Clave WIFI'>*********</td>
            </tr>
            <tr>
              <td data-label='Red WIFI'>DiskCover-Prismanet</td>
              <td data-label='IP LAN'>LocalHost</td>
              <td data-label='Usuario'>Prismanet</td>
              <td data-label='Clave WIFI'>**********</td>
            </tr>
          </tbody>
        </table>
      </div>
  </section>
  <footer>
    <div class="row margin-b-0 text-justificado text-short"><br><br>Este correo electronico fue generado automaticamente a usted desde El Sistema Financiero Contable DiskCover System, porque figura como correo electronico alternativo de DISKCOVER SYSTEM. Nosotros respetamos su privacidad y solamente se utiliza este medio para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa. Este mensaje fue procesado por: Update DiskCover, funcionario que forma parte de la Institucion.<br><br>Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de Servicio al Cliente, podra solicitar ayuda mediante los canales oficiales que detallamos a continuacion: Telefonos: 09-9965-4196/09-8910-5300, Correo: actualizar@diskcoversystem.com.<br><br>Por la atencion que se de al presente quedo de usted.<br><br>Atentamente,<br><br>Walter Vaca Prieto<br><br>Visita: www.diskcoversystem.com<br>QUITO - ECUADOR</div>
  </footer>
</body>
</html>`;   
          xhr.send(params);
  }
</script>
<div class="row mb-2">
  <div class="card card-primary">
    <div class="card-body">
      <div class="row">
          <div class="col-sm-6">
            <h6 class="card-title text-end">NUEVO DESCARGO </h6>   
          </div>
          <div class="col-sm-6 text-end">
            <h7 class="ms-auto"> No. COMPROBANTE  <u id="num"></u></h7>               
          </div>       
      </div>
      <div class="row">
        <div class="col-sm-3"> 
            <b>Num Historia clinica:</b>
            <input type="text" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm" readonly="">      
        </div>
        <div class="col-sm-6">
          <b>Nombre:</b>
          <select class="form-select form-select-sm" id="ddl_paciente" onchange="buscar_cod()">
            <option value="">Seleccione paciente</option>
          </select>
        </div>
        <div class="col-sm-3">
          <b>RUC:</b>
          <input type="text" name="txt_ruc" id="txt_ruc" class="form-control form-control-sm" readonly>             
        </div>   
      </div>
      <hr>
      <div class="row">
          <div class="col-sm-4"> 
            <b>Centro de costos:</b>
            <select class="form-control form-control-sm" id="ddl_cc" onchange="">
              <option value="">Seleccione Centro de costos</option>
            </select>           
          </div>
          <div class="col-sm-2">    
          <b>Numero de pedido</b>
          <input type="text" name="" id="txt_pedido" readonly="" class="form-control form-control-sm" value="<?php echo $num_ped;?>">     
          </div>
          <div class="col-sm-3">
             <b>Fecha:</b>
            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm" value="<?php echo date('Y-m-d')  ?>" onblur="num_comprobante()">                 
          </div>
          <div class="col-sm-3">
            <b>Area de descargo</b>
            <select class="form-select form-select-sm" id="ddl_areas" onchange="validar_area()">
              <option value="">Seleccione motivo de ingreso</option>
            </select>            
          </div>          
      </div>
      <div class="row">
          <div class="col-sm-4"> 
            <b>Cod Producto:</b>
            <select class="form-select form-select-sm" id="ddl_referencia" onchange="producto_seleccionado('R')">
              <option value="">Escriba referencia</option>
            </select>           
          </div>
          <div class="col-sm-5"> 
                <b>Descripcion:</b>
                <select class="form-select form-select-sm" id="ddl_descripcion" onchange="producto_seleccionado('D')">
                  <option value="">Escriba descripcion</option>
                </select>          
              </div> 
          <div class="col-sm-3"> 
            <b>Procedimiento:</b>
            <div class="input-group input-group-sm">
                <textarea class="form-control form-control-sm" style="resize: none;" name="txt_procedimiento" id="txt_procedimiento" readonly=""></textarea>          
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="cambiar_procedimiento()"><i class="bx bx-pencil"></i></button>
                    </span>
              </div>
           
          </div>           
      </div>
       <div class="row">
        <div class="col-sm-3"> 
          <div class="row">
             <div class="col-sm-6"> 
              <b>MIN:</b>
              <input type="text" name="txt_min" id="txt_min" class="form-control form-control-sm"readonly="">
            </div>
            <div class="col-sm-6"> 
              <b>MAX:</b>
              <input type="text" name="txt_max" id="txt_max" class="form-control form-control-sm"readonly="">
            </div>               
          </div>
        </div>               
        <div class="col-sm-2"> 
          <b>Costo:</b>
          <input type="text" name="txt_precio" id="txt_precio" class="form-control form-control-sm" value="0" onblur="calcular_totales();" readonly="">            
        </div>   
        <div class="col-sm-2"> 
          <b>Cantidad:</b>
          <input type="text" name="txt_cant" id="txt_cant" class="form-control form-control-sm" value="1" onblur="calcular_totales();">
        </div>   
        <div class="col-sm-1"> 
          <b>UNI:</b>
          <input type="text" name="txt_unidad" id="txt_unidad" class="form-control form-control-sm" readonly="">            
        </div>
        <div class="col-sm-1"> 
          <b>Stock:</b>
          <input type="text" name="txt_Stock" id="txt_Stock" class="form-control form-control-sm" readonly="">            
        </div>    
        <div class="col-sm-1"> 
          <b>Importe:</b>
          <input type="text" name="txt_importe" id="txt_importe" class="form-control form-control-sm" readonly="">
          <input type="hidden" name="txt_iva" id="txt_iva" class="form-control form-control-sm">            
        </div> 
        <div class="col-sm-2 text-end"><br>
          <button class="btn btn-primary btn-sm" onclick="calcular_totales();Guardar()"><i class="fa fa-arrow-down"></i> Agregar</button>
        </div>
      </div>
    </div>    
  </div>  
</div>
<div class="row">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="table-responsive">
            <input type="hidden" name="" id="txt_num_lin" value="0">
            <input type="hidden" name="" id="txt_num_item" value="0">
            <input type="hidden" name="txt_neg" id="txt_neg" value="false">
            <div id="tabla">
              
            </div>
            

               
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
  

<div class="modal fade" id="modal_procedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cambiar procedimiento</h5>
      </div>
      <div class="modal-body">
         <div class="row">
          <div class="col-sm-12">
            Nombre de procedimiento
            <input type="text" class="form-control form-control-sm" name="txt_new_proce" id="txt_new_proce">
          </div>        
         </div> 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_new_pro();">Guardar</button>
          <button type="button" class="btn btn-default btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>
