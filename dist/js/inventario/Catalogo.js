var tbl_catalogo;
$(document).ready(function()
{
    tbl_catalogo = $('#tablaProductoCatalogo').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        scrollX: true,
        paging:false,
        searching:false,
        info:false,
        scrollY: 330,
        scrollCollapse: true,
    });

    $('#MBoxCtaI').keyup(function(e){ 
        if(e.keyCode != 46 && e.keyCode !=8)
        {
            validar_cuenta_inv(this);
        }
    })

    $('#MBoxCtaF').keyup(function(e){ 
        if(e.keyCode != 46 && e.keyCode !=8)
        {
            validar_cuenta_inv(this);
        }
    })

    asignarHeightPantalla($(".div_filtro"), $("#heightDisponible"))
        $('#imprimir_excel').click(function(){
        var url = '../controlador/inventario/CatalogoC.php?ExcelListarCatalogoInventario=true&'+$("#FormCatalogoCtas").serialize();
        window.open(url, '_blank');
    });

    $('#imprimir_pdf').click(function(){
        var url = '../controlador/inventario/CatalogoC.php?PdfRListarCatalogoInventario=true&'+$("#FormCatalogoCtas").serialize();
        window.open(url, '_blank');
    });
});

function ListarCatalogoInventarioJS(){
    $('#myModal_espera').modal('show');

    tbl_catalogo.destroy();

    tbl_catalogo = $('#tablaProductoCatalogo').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/inventario/CatalogoC.php?ListarCatalogoInventario=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                return $("#FormCatalogoCtas").serialize();
            }, dataSrc: (json) => {
                $('#myModal_espera').modal('hide');
                return json;
            }
        },
        scrollX: true,  // Habilitar desplazamiento horizontal
        //     paging:false,
        //     searching:false,
        //     info:false,
        fixedHeader: true,
        //responsive: true,
        scrollY: '330px',
        scrollCollapse: true,
        columns: [
            { data: 'TC', width: '40px'},
            { data: 'Codigo_Inv', width: '200px'},
            { data: 'Producto', width: '300px'},
            { data: 'PVP', width: '64px'},
            { data: 'Codigo_Barra', width: '200px'},
            { data: 'Cta_Inventario', width: '144px'},
            { data: 'Unidad', width: '136px'},
            { data: 'Cantidad', width: '136px', className: 'text-end'},
            { data: 'Cta_Costo_Venta', width: '144px'},
            { data: 'Cta_Ventas', width: '144px'},
            { data: 'Cta_Ventas_0', width: '144px'},
            { data: 'Cta_Ventas_Ant', width: '144px'},
            { data: 'Cta_Venta_Anticipada', width: '144px'},
            { data: 'IVA', width: '24px'},
            { data: 'INV', width: '24px'},
            { data: 'Codigo_IESS', width: '320px'},
            { data: 'Codigo_RES', width: '136px'},
            { data: 'Marca', width: '240px'},
            { data: 'Reg_Sanitario', width: '200px'},
            { data: 'Ayuda', width: '300px'},
        ]
    });
}