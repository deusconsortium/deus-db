/**
 * Created by jpasdeloup on 03/06/15.
 */


$(document).ready(function() {
    $('#reset').on('click', function() {
        $("#simulation_datatable .individual_filtering").each(function(i) {
            $(this).val("");
        });
        location.reload();
    });

    $('#simulation_datatable').on( 'draw.dt', function () {

        $('#simulation_datatable .btn').click(function(e) {

            e.stopPropagation();
            window.open($(this).attr("href"),"_blank", "height=400,width=800");
            //
            //
            //$.ajax({
            //    type: 'GET',
            //    url: $(this).attr("href"),
            //    success: function(html) {
            //        $(this).avgrund({
            //            openOnEvent: false,
            //            showClose: true, // switch to 'true' for enabling close button
            //            height: 300,
            //            width: 750,
            //            template: html //'<p>So implement your design and place content <b>' + id + '</b> here!</p>'
            //        });
            //    }
            //});
            return false;
        });

    } );

    $('#simulation_datatable').on('xhr.dt', function ( e, settings, json ) {
        $("#simulation_datatable .individual_filtering").each(function(i) {
            var currvalue = $(this).val();
            var option = $(this).html('<option value="">All</option>');
            if(json.filters[i]) {
                for(key in json.filters[i]) {
                    var selected = key == currvalue ? ' selected="selected"' : '';
                    option.append('<option value="'+key+'" '+ selected + '>'+json.filters[i][key]+'</option>');
                };
            }
        });
    });

} );