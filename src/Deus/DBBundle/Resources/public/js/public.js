/**
 * Created by jpasdeloup on 03/06/15.
 */


$(document).ready(function() {

    $('#simulation_datatable').on( 'draw.dt', function () {

        $('#simulation_datatable .btn').click(function(e) {
            e.stopPropagation();
            $.ajax({
                type: 'GET',
                url: $(this).attr("href"),
                success: function(html) {
                    $(this).avgrund({
                        openOnEvent: false,
                        showClose: true, // switch to 'true' for enabling close button
                        height: 300,
                        width: 750,
                        template: html //'<p>So implement your design and place content <b>' + id + '</b> here!</p>'
                    });
                }
            });
            return false;
        });

    } );
} );