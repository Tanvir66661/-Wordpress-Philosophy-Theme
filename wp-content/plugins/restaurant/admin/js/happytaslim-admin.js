;(function( $ ) {
	'use strict';

	jQuery(document).ready(function($) {
        

        // Check to make sure the input box exists
        if( 0 < $('#selectdate').length ) {
            $('#selectdate').datepicker({
            	dateFormat : 'dd-mm-yy'
            });
            $('#ui-datepicker-div').addClass('notranslate');
        } // end if
        // Check to make sure the input box exists
        if( 0 < $('#selectdates').length ) {
            $('#selectdates').datepicker({
                dateFormat : 'dd-mm-yy'
            });
            $('#ui-datepicker-div').addClass('notranslate');
        } // end if

	});

	//jQuery('.table-chair').draggable();

    $('.addpeople').click(function() {
        if ($(this).is(':checked')) {
            var className = $(this).attr('peopleClass');
            $('.'+className).attr('checked', true);
        } else {
            var className = $(this).attr('peopleClass');
             $('.'+className).attr('checked', false);
        }
    });

    $('#ajax_table_submit form').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        console.log(data);
        $.post(taslimAjax.ajaxurl, data, function(response) {
            if (response.success) {
                console.log(response);
                $("#taslim_ajax_dispaly").html(response.data.message);
               // $('.happytaslim_be_popup_form_content').html(response.data.message)
            } else {
                alert(response.data.message);
            }
        })
        .fail(function() {
            alert(taslimAjax.error);
        })
    });


    $('#taslim_ajax_dispaly').on('submit', 'form#ajaxappointment', function(e) {
        e.preventDefault();
        $('.taslim-loading').show('fast');
        var data = $(this).serialize();
        console.log(data);
        $.post(taslimAjax.ajaxurl, data, function(response) {
            if (response.success) {
                $('.taslim-loading, #submit_appointment_final').hide('fast');
                console.log(response);
                 $('.happytaslim_be_popup_form_content').html(response.data.message)

                    setTimeout(function() {
                        closePopupForm();
                    }, 2000);

                    location.reload(true);

               // $("#taslim_ajax_dispaly").html(response.data.body);
            } else {
                $('.taslim-loading').hide('fast');
                console.log(response);

                if (response.data.confirm == 'yes') {
                    var reservation_overide = confirm(response.data.message+ " Wenst u toch verder te gaan?");
                    if (reservation_overide == true) {
                        //var confirm_yes = '<input type="hidden" name="confirm_save" value="yes">'
                        $('#taslim_ajax_dispaly form#ajaxappointment').append('<input type="hidden" name="confirm_save" value="yes">');
                        $('#taslim_ajax_dispaly form#ajaxappointment').submit();
                    }else {
                        closePopupForm();
                    }
                }else {
                    alert(response.data.message);
                }

            }
        })
        .fail(function() {
            alert(taslimAjax.error);
        })
    });


    function closePopupForm() {
        var t = $(".happytaslim_be_popup_form_wrap.enable");
        $("body").removeClass("disable_scroll"); 
        t.removeClass("enable");
        setTimeout(function() {
            t.remove()
        }, 300)
    };

    $("#taslim_ajax_dispaly").on("click", ".happytaslim_be_popup_form_button a.cancel", function() {
        return closePopupForm()
    })

    $("#taslim_ajax_dispaly").on("click", "span.closer" , function() {
        return closePopupForm()
    })

/*
    $("#taslim_ajax_dispaly").on("change", "#time_slot" , function() {
   
        var data;
        data = {
            action: "get_selected_end_time",
            time_slot : $( "#time_slot option:selected" ).text(),
        };

        $('.taslim-loading').show('fast');
        console.log(data);
        $.post(taslimAjax.ajaxurl, data, function(response) {
            if (response.success) {
                $('.taslim-loading').hide('fast');
                //alert(response.data.message);
                $('#end_time_slot').html(response.data.message);
            } else {
                $('.taslim-loading').hide('fast');
                alert(response.data.message);
            }
        })
        .fail(function() {
            alert(taslimAjax.error);
        })

    })

*/
/*
    $(".happytaslim_be_popup_form_wrap.enable").find("span.closer").on("click", function() {
        return closePopupForm()
    })
    $(".clickontable").off().on("click", function() {

        $("body > .happytaslim_be_popup_form_wrap").addClass("happytaslim_be_popup_form_service");
        $("body > .happytaslim_be_popup_form_wrap").addClass("enable");

    })

*/

//console.log(taslimAjaxform.formlayout);

    $('#ajax_update_appointment form').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        console.log(data);
        $.post(taslimAjax.ajaxurl, data, function(response) {
            if (response.success) {
                console.log(response);
                $("#taslim_ajax_dispaly").html(response.data.message);
              //  $('.happytaslim_be_popup_form_content').html(response.data.message)
            } else {
                alert(response.data.message);
            }
        })
        .fail(function() {
            alert(taslimAjax.error);
        })
    });

    function dropableTable(data){
        console.log(data.attr("tableNumber"));
        var img = data.find('img').attr('src');
        var tableDesign = '<div class="table-chair edit_table_position" style="position: absolute; top: 30px; left: 0px"><span class="table_remove">X</span><img src="'+img+'"><span class="table_serial"><input type="text" name="tableid[]" value="0"><input type="text" name="chair[]" value="'+data.attr("tableNumber")+'"  style="margin-top: 6px;"></span><input type="hidden" name="tablefor[]" value="'+data.attr("tablefor")+'"><input type="hidden" name="image[]" value="'+data.attr("tableimg")+'"><input type="hidden" name="style[]" class="table_style" value=""></div>';
        return tableDesign;
    }
    
    $('.tablelist').on('click', function(){
        var data = $(this);
        $(".table-area").append(dropableTable(data));
        $( ".edit_table_position" ).draggable({
          grid: [ 1, 1 ],
          drag: function( event, ui ) {
                var position = $(this).css('position');
                var top = $(this).css('top');
                var left = $(this).css('left');
                $(this).find('input.table_style').val('position: '+position+'; top: '+top+'; left: '+left+';');

          }
        });
    });

        var ishaveclass = $( "#table_section" ).hasClass( "table-area" );
        if (ishaveclass == true) {
            $( ".edit_table_position" ).draggable({
              grid: [ 1, 1 ],
              drag: function( event, ui ) {
                    var position = $(this).css('position');
                    var top = $(this).css('top');
                    var left = $(this).css('left');
                    $(this).find('input.table_style').val('position: '+position+'; top: '+top+'; left: '+left+';');
              }
            });
        }

    $('.table-area').on('click', '.table_remove', function() {
        $(this).parent().remove();
    });

    $('.customer_in_out').on('click', function(e) {
        e.preventDefault();

        var getstatus = $(this).attr('status');
        var getParentid = $(this).attr('parentid');
        var customerid = $(this).attr('customerid');        

        var data;
        data = {
            action: "update_reservation_status",
            customer : customerid,
            status : getstatus
        };

        $('.taslim-loading').show('fast');
        console.log(data);
        $.post(taslimAjax.ajaxurl, data, function(response) {
            if (response.success) {
                $('.taslim-loading').hide('fast');
                location.reload(true);
            } else {
                $('.taslim-loading').hide('fast');
                alert(response.data.message);
            }
        })
        .fail(function() {
            alert(taslimAjax.error);
        })

        if ( getstatus == 'itisfree' ) {
            $('#'+getParentid).addClass('itisnotfree');
            $('#'+getParentid).removeClass('itisfree');
            $(this).text('VRIJ');
            $(this).attr( 'status','itisnotfree');            

        }

        if ( getstatus == 'itisnotfree' ) {
            $('#'+getParentid).addClass('itisfree');
            $('#'+getParentid).removeClass('itisnotfree');
            $(this).text('BEZET');
            $(this).attr( 'status','itisfree');            
        }
    });


    $('a.staff_edit').on('click', function(e) {
        e.preventDefault();
        var data;
        data = {
            action: "staff_update_reservation",
            customer_id : $(this).attr('customerId'),
            tableLocation : $(this).attr('tableLocation'),
            tableList : $(this).attr('tableList'),
            rpeople : $(this).attr('rpeople'),
            _wpnonce : $(this).attr('staffnonce'),
        };
        console.log(data);
        $.post(taslimAjax.ajaxurl, data, function(response) {
            if (response.success) {
                console.log(response);
                $("#taslim_ajax_dispaly").html(response.data.message);
              //  $('.happytaslim_be_popup_form_content').html(response.data.message)
            } else {
                alert(response.data.message);
            }
        })
        .fail(function() {
            alert(taslimAjax.error);
        })
    });


    $('#taslim_ajax_dispaly').on('submit', 'form#staff_reservation_update', function(e) {
        e.preventDefault();
        $('.taslim-loading').show('fast');
        var data = $(this).serialize();
        console.log(data);
        $.post(taslimAjax.ajaxurl, data, function(response) {
            if (response.success) {
                $('.taslim-loading, #submit_appointment_final').hide('fast');
                console.log(response);
                 $('.happytaslim_be_popup_form_content').html(response.data.message)
                    setTimeout(function() {
                        closePopupForm();
                    }, 2000);
                  //  location.reload(true);
                    $('form#todayfilter').submit();

            } else {
                $('.taslim-loading').hide('fast');
                    alert(response.data.message);
            }
        })
        .fail(function() {
            alert(taslimAjax.error);
        })
    });

    $('form#tdedittablef').on('submit', function(e) {
       // alert('wow');

        var data = $(this).serializeArray();
        var tableIds = data.filter(a=>a.name=='tableid[]').map(a=>a.value)
        if(tableIds.length!=Array.from(new Set(tableIds)).length){
        alert("Error: dit tafelnummer bestaat al. Kies een ander nummer.")
        e.preventDefault();
        }

    });

})( jQuery );
