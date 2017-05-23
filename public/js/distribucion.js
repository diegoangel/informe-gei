var f = "distribucion-sector";
var sector_id = 'all';
var sector_nombre = 'all';
var subactividad_nombre = 'all';

var chart,indicador_id,ano,dis_sector_id,evo_sector_id,last_f,last_evo_sector_id;

var arrSectores = {};
arrSectores[1] = 'Agricultura, ganadería, silvicultura y otros usos de la tierra';
arrSectores[2] = 'Energía';
arrSectores[3] = 'Procesos industriales y uso de productos';
arrSectores[4] = 'Residuos';


$(document).ready(function(){

    
    for (i = 1990; i <= 2014; i++) 
    { 
        $("#select_ano").append("<option value='"+i+"'>"+i+"</option>");
    }

    $("#nav .select.ano select").val( '2014' );
    $("#nav .select.ano span").html( '2014' );
    $("#nav .select.ano select option").show();

    
    $("#nav").on("click",".updownarrowmain",function(){

        $(this).toggleClass('activo').siblings(".content_distribucion_evolucion").slideToggle();
        // $(this).addClass('activo').siblings(".content_distribucion_evolucion").show();


    });

    // SLIDES DE CATEGORIAS
    $("#nav .distribucion").on("click","h3.slider",function(){


        if( !$(this).parent(".select").parent(".updownarrow").hasClass("anobox") )
        {
            $("#nav .select.ano select").val( '2014' );
            $("#nav .select.ano span").html( '2014' );
            $("#nav .select.ano select option").show();

            $(".distribucion .select").removeClass("activo").children(".labels:visible").slideUp();

            // CUANDO CLICKEA LOS SLIDERS QUE NO SON EL Año
            f = $(this).attr('data-f');

            if(f == 'distribucion-gases' || f == 'distribucion-gases-sector' || f == 'distribucion-sankey')
            {
                $("#nav .select.ano select").val( '2014' );
                $("#nav .select.ano select option").hide().filter("[value=2014]").show();
                $("#nav .select.ano span").html( '2014' );
            }
        
        }

        $(this).parent(".select").toggleClass("activo").children(".labels").slideToggle();


    });

    $("#nav .evolucion").on("click","h3.slider",function(){
        f = $(this).attr('data-f');
    });

    // RADIO BUTTONS
    $("#nav").on("click","label.sec",function(){

        $("#nav label.sec").removeClass("activo");  
        $(this).addClass("activo");

    });


    // RADIO BUTTONS DE DISTRIBUCION
    $("#nav .nav_emisiones").on("click","label.sec",function(){

        f = 'distribucion-sector';

    });




    // SELECTOR DE AÑO
    $("#nav").on("change","select.ano",function(){

        $("#nav .select.ano span").html( $(this).val() );

    });


    $("#nav .main.distribucion").on("click","#btn-ver_resultado",ver_resultado);

    $("#nav .main.evolucion").on("click","#btn-ver_resultado",function(){ f = 'evolucion-sector'; ver_resultado(); });

    // $(".select.porsectores").addClass('activo').children(".labels").slideToggle();;

    $("#nav .main.evolucion").on("change","input[name=evo_sector_id]",function(){
        f = 'evolucion-sector';
    });



    ////////////////////////////////////
    // BOTONES DE ARRIBA DE LA SELECCION LATERAL
    ////////////////////////////////////

    $("#nav").on("click",".emisionesindicadores",function(){
        
        // COLORES BOTONES
        $(".emisionesindicadores").removeClass("activo");
        $(this).addClass("activo");



        if( $(this).hasClass("indicadores") )
        {

            $("#nav .nav_emisiones").hide();
            $("#nav .nav_indicadores").show();

            // COLOR TITULO PRINCIPAL DEL GRAFICO
            $("#body .info h1").addClass("indicadores");
            $("#body .info h1").removeClass("emisiones");
        }
        else
        {
            $("#nav .nav_emisiones").show();
            $("#nav .nav_indicadores").hide();

            // COLOR TITULO PRINCIPAL DEL GRAFICO
            $("#body .info h1").addClass("emisiones");
            $("#body .info h1").removeClass("indicadores");
        }

    });

    $("#nav .nav_indicadores").on("click","h3.slider",function(){

        $("#nav .nav_indicadores .select").removeClass("activo");
        $(this).parent(".select").addClass("activo");
        
        // f = $(this).attr('data-f');
        f = 'indicador';
        indicador_id = $(this).attr('data-indicador');

        ver_resultado();

    });


    // ARRANCO CON EL GRAFICO DE SECTORES
    $(".select.porsectores").addClass("activo").children(".labels").show();
    ver_resultado();


});

function volver()
{
    // ME FIJO LOS VALORES DE F Y VOY AL ANTERIOR
    
    if(f=='evolucion-sector-subactividad')
    {
        f = 'evolucion-sector';
    }

    if(f=='evolucion-sector-subactividad-categoria')
    {
        f = 'evolucion-sector-subactividad';
    }


    graficar();
}



function ver_resultado()
{

    // DATOS DEL FILTRO
    ano = $("#select_ano").val();
    dis_sector_id = $("input[type=radio][name=dis_sector_id]").filter(':checked').val();
    evo_sector_id = $("input[type=radio][name=evo_sector_id]").filter(':checked').val();


    if(f == "distribucion-sector" || f == "distribucion-gases" || f == "distribucion-gases-sector" || f == "distribucion-sankey")
    {
        // CAMBIO DATOS DUROS DE PANTALLA
        $("#chart_ano").html(ano);  
    }

    
    // GRAFICO 1
    // DISTRIBUCION DE GEI ENTRE TODOS LOS SECTORES. TORTA
    if(f == "distribucion-sector" && dis_sector_id == 'all')
    {   
        $("#chart_title").html('Distribución de GEI');
    }

        
    // CUANDO MUESTRO EL SANKEY ESCONDO LOS GRAFICOS
    if(f == "distribucion-sankey")
    {
        $("#chart").hide();
        $("#chart_sankey").show();
        $("#chart_back").hide();

        $("#box_chart_sector").show();

        $("#chart_title").html('Distribución de GEI por uso final');
        $("#chart_sector").html('Todos');

    }
    else
    {
        $("#chart").show();
        $("#chart_sankey").hide();
        graficar();
    }

    

}

function get_chart_width()
{
    // ESTE ES EL ESPACIO QUE TENGO DISPONIBLE
    var width = $("#body .content").width();

    console.log(width);

    return(width);

}


function get_chart_height()
{
    // ESTE ES EL ESPACIO QUE TENGO DISPONIBLE
    var height = $(document).height() - $("footer").height() - $("footer").height() - ($("#body .info").height() * 2) - 17 - 17 - 50;

    var exceso = 0;

    if(height > 600)
    {
        exceso = height - 600;
        height = 600;
    }

    if(exceso > 0)
    {
        $("#chart").css({ marginTop: (exceso/2)+'px' });
    }

    height = (height<400) ? 400 : height;

    return(height);

}


function graficar(){

    $("#chart_psd3").hide();

    $("#box_chart_subactividad").hide();
    $("#box_chart_sector").hide();
    $("#chart_unidad").html("MtCO₂eq");

    $("#chart_back").hide();

    if(f != 'indicador')
    $("#chart_descripcion").hide();

    // DISTRIBUCION DE GEI ENTRE TODOS LOS SECTORES. CORONA
    if(f == "distribucion-sector" && dis_sector_id != 'all')
    {   
        // GRAFICO CORONA
        $("#box_chart_sector").show();
        $("#chart_title").html('Distribución de GEI por sector');
        $("#chart_sector").html(arrSectores[dis_sector_id]);
        
    }

    if(f == "distribucion-gases")
    {
        $("#box_chart_sector").show();
        $("#chart_title").html('Distribución de GEI por tipo de gases');
        $("#chart_sector").html('Todos');
    }


    if(f == "distribucion-gases-sector")
    {
        $("#box_chart_sector").show();
        $("#chart_title").html('Distribución de GEI por tipo de gases por sector');
        $("#chart_sector").html('Todos');
    }

    if(f == "evolucion-sector" && evo_sector_id == 'all')
    {
        $("#box_chart_sector").show();
        $("#chart_sector").html('Todos');

        $("#chart_title").html('Evolución de GEI');
        $("#chart_ano").html("1990 - 2014");
        sector_id = evo_sector_id;
    }


    if(f == "evolucion-sector" && evo_sector_id != 'all')
    {
        sector_id = evo_sector_id;
        
        $("#box_chart_sector").show();
        $("#chart_sector").html(arrSectores[sector_id]);

        $("#chart_title").html('Evolución de GEI por sector');
        $("#chart_ano").html("1990 - 2014");
    }

    if(f == "evolucion-sector-subactividad" && evo_sector_id != 'all')
    {
        sector_id = evo_sector_id;
        $("#chart_title").html('Evolución de GEI por sector - subactividad');
        $("#chart_ano").html("1990 - 2014");

        $("#box_chart_sector").show();
        $("#chart_sector").html(arrSectores[sector_id]);

        $("#chart_back").show();
    }

    if(f == "evolucion-sector-subactividad-categoria" && evo_sector_id != 'all')
    {
        sector_id = evo_sector_id;
        $("#chart_title").html('Evolución de GEI por subactividad - categoría');
        $("#chart_ano").html("1990 - 2014");

        $("#box_chart_sector").show();
        $("#chart_sector").html(arrSectores[sector_id]);

        $("#box_chart_subactividad").show();
        // LA SUBACTIVIDAD ESTA ADENTRO DEL AJAX

        $("#chart_back").show();
    }

    //////////////////////////////////////////
    // INDICADORES
    //////////////////////////////////////////

    if(f == "indicador")
    {
        
        $("#box_chart_sector").hide();
        $("#chart_ano").html("1990 - 2014");
    }

    var char_height = get_chart_height();
    var char_width  = get_chart_width();

    // if(f ==  "distribucion-sector" && $("input[type=radio][name=dis_sector_id]").filter(':checked').val() == 'all')
    if(f ==  "distribucion-sector" && dis_sector_id == 'all')
    {

        var params = {
            sector_id: 'all',
            ano: $("#select_ano").val(),
            f: f
        }

        $.get("/informe/distribucion-sectores/" + params.ano, {}, function(data) {

            console.log(data);

            chart = c3.generate({

                size: {
                    height: char_height
                },

                data: {
                    // iris data from R
                    columns: [
                        data.sector_1,
                        data.sector_2,
                        data.sector_3,
                        data.sector_4,
                    ],

                    type : 'pie',

                    onmouseover: function (d) { 

                        html = "<h2 style='color:"+data.colores[d.index]+"''>"+eval("data.sector_"+(d.index+1)+"[0]")+"</h2>"+data.descripciones[d.index];

                        $("#chart_descripcion").show().html(html); 
                    },

                    onmouseleave: function (d) { $("#chart_descripcion").html('').hide(); },

                },

                color: {
                        pattern: data.colores,
                },

                legend:
                {
                    item:
                    {
                        onclick: function(){},
                    },
                },




                tooltip: {

                    
                    
                    contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
                        
                        var $$ = this, config = $$.config,
                          
                        titleFormat = config.tooltip_format_title || defaultTitleFormat,
                        nameFormat = config.tooltip_format_name || function (name) { return name; },
                        valueFormat = config.tooltip_format_value || defaultValueFormat, 
                        text, i, title, value, name, bgcolor;
                        
                        for (i = 0; i < d.length; i++) {
                            
                            if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }

                            if (! text) {
                                title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                                text = "<table class='" + $$.CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
                                text = "<table class='" + $$.CLASS.tooltip + "'>";
                            }

                            name = nameFormat(d[i].name);
                            value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);
                            bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

                            text += "<tr>";
                            text += "<td colspan='2' align='center'><span style='background-color:" + bgcolor + "'></span>" + name + "</td>";
                            text += "</tr><tr>"
                            text += "<td align='center'>" + value + "</td>";
                            text += "<td align='center'>" + d[i].value + " MtCO₂eq</td>";
                            text += "</tr>";
                        }
                        return text + "</table>";
                    }

                }

            });

        });

    }

    // if(f ==  "distribucion-sector" && $("input[type=radio][name=dis_sector_id]").filter(':checked').val() != 'all')
    if(f ==  "distribucion-sector" && dis_sector_id != 'all')
    {
        var graph_data;

        var params = {
            sector_id: dis_sector_id,
            ano: $("#select_ano").val(),
            f: f
        }

        char_height -= 40;
        char_side = (char_height <= char_width) ? char_height : char_width;

        
        $("#chart").hide();
        $("#chart_psd3").html('').show();

        $.get("informe/" + params.f + "/" + params.ano + "/" + params.sector_id, {}, function(resp) {

            graph_data = resp;

            console.log(graph_data);

        
        
        // Create config 
        var config = {

            containerId: "chart_psd3",
            
            width: char_side,

            height: char_side,

            data: graph_data,
            
            // data: [

            //  {
            //      value: 20,
            //      label: "Maharashtra",
            //      inner: [

            //          {
              //                value: 10,
              //                label: "Pune",
              //                inner: [{
                 //                 value: 5,
                 //                 label: "Surat"
                 //             }, {
                 //                 value: 5,
                 //                 label: "Rajkot"
                 //             }]
              //            }, 
              //            {
              //                value: 10,
              //                label: "Mumbai",
              //                inner: [{
                 //                 value: 2,
                 //                 label: "Surat"
                 //             }, {
                 //                 value: 8,
                 //                 label: "Rajkot"
                 //             }]
              //            }
            //      ]
            //  }, 
            //  {
            //      value: 50,
            //      label: "Gujarat",
            //      inner: [{
            //          value: 20,
            //          label: "Surat"
            //      }, {
            //          value: 30,
            //          label: "Rajkot"
            //      }]
            //  }

            // ],


            label: function(d) {
                return d.data.label + ":" + d.data.value;
            },

            tooltip: function(d) {
                return "<p>There are " + d.value + " medical colleges in " + d.label + ".</p>";
            },

            donutRadius: 50,

            transition: "linear",
    
            transitionDuration: 500,

            labelColor: "white"

        };
      
        // Draw chart
        var samplePie = new psd3.Pie(config);

        });


    }


    if(f ==  "distribucion-gases")
    {
        var params = {
            ano: $("#select_ano").val(),
            f: f
        }

        $.get("informe/" + params.f + "/" + params.ano, {}, function(data) {



            chart = c3.generate({

                size: {
                    height: char_height
                },

                data: {
                    x : 'x',

                    // url: data_url,

                    columns: [
                        data.gases,
                        data.valores,
                    ],

                    groups: [
                        ['Gases']
                    ],

                    type: 'bar',

                    color: function (color, d) {

                        return data.colores[d.x];

                    },

                },

                legend: {
                    show: false
                },

                bar: {
                    width: {
                        ratio: 0.5 // this makes bar width 50% of length between ticks
                    },
                },

                axis: {
                    x: {
                        type: 'category' // this needed to load string x value
                    }
                },

                tooltip: {



                    contents: function (d, defaultTitleFormat, defaultValueFormat, color) {

                        
                        
                        var $$ = this, config = $$.config,
                          
                        titleFormat = config.tooltip_format_title || defaultTitleFormat,
                        nameFormat = config.tooltip_format_name || function (name) { return name; },
                        valueFormat = config.tooltip_format_value || defaultValueFormat, 
                        text, i, title, value, name, bgcolor;


                        
                        for (i = 0; i < d.length; i++) {
                            
                            if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }

                            if (! text) {
                                title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                                text = "<table class='" + $$.CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
                                text = "<table class='" + $$.CLASS.tooltip + "'>";
                            }

                            console.log(d);

                            name = nameFormat(d[i].name);
                            value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);
                            bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

                            text += "<tr>";
                            text += "<td align='center'>" + value + " MtCO₂eq</td>";
                            text += "</tr>";
                        }
                        return text + "</table>";
                    }

                }




            });

        });

    }


    if(f ==  "distribucion-gases-sector")
    {

        var params = {
             ano: $("#select_ano").val(),
            f: f
        }

        $.getJSON("informe/" + f +"/" + ano, {}, function(data) {

            
            chart = c3.generate({

                size: {
                    height: char_height
                },

                data: {
                    x : 'x',
                    columns: [
                        data.column_1,
                        data.column_2,
                        data.column_3,
                        data.column_4,
                        data.column_5,

                    ],
                    groups: [
                        ['Agricultura, ganadería, silvicultura y otros usos de la tierra','Energía','Procesos industriales y uso de productos', 'Residuos']
                    ],
                    type: 'bar',

                },

                axis: {
                    x: {
                        type: 'category' // this needed to load string x value
                    }
                },



                color: {
                    pattern: ['#54bdb4', '#f44652', '#f87652', '#9189b8' ]
                },

                tooltip: {

                    // format: {
                    //     // title: function (d) { return 'Data ' + d; },
                    //     value: function (value, ratio, id) {
                            
                    //      txt = value+'MtCO<sub>2</sub>eq '

                    //      return txt;

                    //     }
                    // }

                    contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
                        
                        var $$ = this, config = $$.config,
                          
                        titleFormat = config.tooltip_format_title || defaultTitleFormat,
                        nameFormat = config.tooltip_format_name || function (name) { return name; },
                        valueFormat = config.tooltip_format_value || defaultValueFormat, 
                        text, i, title, value, name, bgcolor;
                        
                        for (i = 0; i < d.length; i++) {
                            
                            if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }

                            if (! text) {
                                title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                                text = "<table class='" + $$.CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
                                text = "<table class='" + $$.CLASS.tooltip + "'>";
                            }

                            name = nameFormat(d[i].name);
                            value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);
                            bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

                            text += "<tr>";
                            text += "<td><span style='background-color:" + bgcolor + "'></span>" + name + "</td>";
                            text += "<td>" + d[i].value + " MtCO₂eq</td>";
                            text += "</tr>";
                        }
                        return text + "</table>";
                    }
                }

            });

        });
    }


    if(f ==  "evolucion-sector" && sector_id == 'all')
    {
        var params = {

                    f:      f

                    }

        $.getJSON("_post/ajax.php",params,function(data){

            chart = c3.generate({

                size: {
                    height: char_height
                },

                data: {
                    
                    x : 'x',

                    columns: [

                        data.column_1,
                        data.column_2,
                        data.column_3,
                        data.column_4,
                        data.column_5,

                    ],

                    // types: {

                    //     'Agricultura, ganadería, silvicultura y otros usos de la tierra': 'line',
                    //     'Energía': 'line',
                    //     'Procesos industriales y uso de productos': 'line',
                    //     'Residuos': 'line',
                    // },

                    // groups: [
                    //     ['Agricultura, ganadería, silvicultura y otros usos de la tierra','Energía','Procesos industriales y uso de productos','Residuos']
                    // ],


                },

                color: {
                    pattern: data.colores,
                },

                point: {
                    
                    r: 3,
                },

                tooltip: {

                    format: {
                        // title: function (d) { return 'Data ' + d; },
                        value: function (value, ratio, id) {
                            
                            txt = value+' MtCO₂eq'

                            return txt;

                        }
                    }

                },

               //      contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
                        
               //       var $$ = this, config = $$.config,
                          
                        // titleFormat = config.tooltip_format_title || defaultTitleFormat,
                        // nameFormat = config.tooltip_format_name || function (name) { return name; },
                        // valueFormat = config.tooltip_format_value || defaultValueFormat, 
                        // text, i, title, value, name, bgcolor;
                        
                        // for (i = 0; i < d.length; i++) {
                            
                        //  if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }

                        //  if (! text) {
                        //      title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                        //      text = "<table class='" + $$.CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
                        //      text = "<table class='" + $$.CLASS.tooltip + "'>";
                        //  }

                        //  name = nameFormat(d[i].name);
                        //  value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);
                        //  bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

                        //  text += "<tr>";
                        //  text += "<td><span style='background-color:" + bgcolor + "'></span>" + name + "</td>";
                        //  text += "<td align='right'>" + d[i].value + " MtCO₂eq</td>";
                        //  text += "</tr>";
                        // }

                        // return text + "</table>";

               //       }
               //  }

            });

            

        });
    }

    if(f ==  "evolucion-sector" && sector_id != 'all')
    {
        var params = {

                    f:          f,
                    sector_id:  sector_id

                    }

        $.getJSON("_post/ajax.php",params,function(data){

            chart = c3.generate({

                size: {
                    height: char_height
                },

                data: {
                    
                    x : 'x',

                    columns: [

                        data.column_1,
                        data.column_2,

                    ],

                    type: 'line',
                    
                    onclick: function (d, element) { 

                        $("#chart_back").show();
                        
                        // CLICKEA EN UN SECTOR
                        f = 'evolucion-sector-subactividad';
                        sector_nombre = d.id;

                        graficar();

                    },

                },

                axis: {
                    x: {
                        

                    },
                },

                // point: {show: false},

                color: {
                    pattern: data.colores,
                },

                point: {
                    
                    r: 3,
                },

                tooltip: {

                    format: {
                        // title: function (d) { return 'Data ' + d; },
                        value: function (value, ratio, id) {

                            value = Math.round(value*100)/100;
                            
                            txt = value+' MtCO₂eq'

                            return txt;

                        }
                    }

                },
                

            });

            

        });
    }


    if(f == "evolucion-sector-subactividad" && sector_nombre != 'all')
    {
        var params = {

                    f:      f,
                    sector_nombre: sector_nombre

                    }

        $.getJSON("_post/ajax.php",params,function(data){

            var columns = [];

            for(i=1;i<20;i++)
            {
                if(typeof (eval('data.column_'+i)) != 'undefined')
                {
                    columns.push( eval('data.column_'+i) );
                }
            }



            chart = c3.generate({

                size: {
                    height: char_height
                },

                data: {
                    
                    x : 'x',

                    columns: columns,

                    type:'line',

                    // groups: [
                    //     data.groups
                    // ],

                    onclick: function (d, element) { 


                        // CLICKEA EN UN SECTOR
                        f = 'evolucion-sector-subactividad-categoria';
                        subactividad_nombre = d.id;

                        graficar();

                    },
                    

                },

                grid: {
                     y: {
                        lines: [{ value: 0 }] // add the value you want
                    }
                },

                color: {
                    pattern: ['#a8cd53', '#ed4d90', '#41b87b', '#0087a1', '#27b8a7', '#f47060', '#f9ad4e', '#f15651', '#f8bb99', '#9da0a0', '#91709e', '#6d5575', '#0000ff', '#00ffff', '#ff00ff', '#333333' ]
                },

                point: {
                    
                    r: 3,
                },

                tooltip: {

                    format: {
                        // title: function (d) { return 'Data ' + d; },
                        value: function (value, ratio, id) {

                            value = Math.round(value*100)/100;
                            
                            txt = value+' MtCO₂eq'

                            return txt;

                        }
                    }

                },

            });

            

        });
    }

    if(f == "evolucion-sector-subactividad-categoria" && sector_nombre != 'all')
    {
        var format = '';

        if(subactividad_nombre == 'Producción de cal') format = '.2f';

        var params = {

                    f: f,
                    sector_nombre: sector_nombre,
                    subactividad_nombre: subactividad_nombre

                    }

        $("#chart_subactividad").html( subactividad_nombre );
        
        $.getJSON("_post/ajax.php",params,function(data){

            var columns = [];

            for(i=1;i<20;i++)
            {
                if(typeof (eval('data.column_'+i)) != 'undefined')
                {
                    columns.push( eval('data.column_'+i) );
                }
            }


            chart = c3.generate({

                size: {
                    height: char_height
                },

                data: {
                    
                    x : 'x',

                    columns: columns,

                    type:'line',

                    // groups: [
                    //     data.groups
                    // ],

                    //onclick: function (d, element) { console.log("onclick", d, element); },

                },

                grid: {
                     y: {
                        lines: [{ value: 0 }] // add the value you want
                    }
                },



                color: {
                    pattern: ['#a8cd53', '#ed4d90', '#41b87b', '#0087a1', '#27b8a7', '#f47060', '#f9ad4e', '#f15651', '#f8bb99', '#9da0a0', '#91709e', '#6d5575', '#0000ff', '#00ffff', '#ff00ff', '#333333' ]
                },

                point: {
                    
                    r: 3,
                },

                axis: {

                    y: {
                        tick: {
                            format: d3.format(format)
                        }
                    },
                },

                tooltip: {

                    format: {
                        // title: function (d) { return 'Data ' + d; },
                        value: function (value, ratio, id) {

                            value = Math.round(value*1000)/1000;
                            
                            txt = value+' MtCO₂eq'

                            return txt;

                        }
                    }

                },

            });

            

        });
    }



    if(f ==  "indicador")
    {
        //'.2f';

        var format = '';

        if(indicador_id == 3 || indicador_id == 4 || indicador_id == 6 || indicador_id == 8) format = '.2f';

        var params = {

                    f:      f,
                    indicador_id: indicador_id

                    }



        $.getJSON("_post/ajax.php",params,function(data){

            $("#box_chart_title").show();
            $("#chart_title").html(data.indicador.nombre);
            $("#chart_unidad").html(data.unidad);
            $("#chart_descripcion").show().html(data.descripcion);

            char_height = char_height - $("#chart_descripcion").outerHeight() - 34;

            chart = c3.generate({

                size: {
                    height: char_height
                },

                data: {
                    
                    x : 'x',

                    columns: [

                        data.column_1,
                        data.column_2,
                    ],

                    type: 'line',

                },

                color: {
                    pattern: [ data.colores ],
                },

                point: {
                    
                    r: 3,
                },

                axis: {

                    y: {
                        tick: {
                            format: d3.format(format)
                        }
                    },
                },



            });

            

        });
    }


    // chart_resize(chart);

}