jQuery(document).ready( function($) {
  var output = '';

  /* CREATE NEW TICKET */
  $('html').on('click','#create_ticket',function(e) {
      $(this).attr('disabled', true);
      $.ajax({
            type: "post",
            url: myAjax.url,
            dataType: 'json',
            data: $('#create_ticket_form').serialize() + "&action=create_ticket&nonce="+ myAjax.nonce,
            success: function(data){ //so, if data is retrieved, store it in html
                    if (data) {
                          var ticket_number = data.ticket_number;
                          var post_id = data.post_id;
                          $('#ticket_number').val(ticket_number);
                          $('#start_ticket_upload').trigger( "click" );
                          $('#display_ticket_number').html('Ticket no.' + post_id);
                          $('#msg-alert-1').fadeIn();
                          $('.client_nav .nav-item a.nav-link')[0].click();
                      }
                    }
        }); 
  });

  /* SAVE TICKET */
  $('html').on('click','#revo_save_ticket',function(e, dept) {
      $(this).attr('disabled', true);
        /*CARGO INFO FORM */
      var formData = $('#form_ticket_details, #form_client_documents' ).serialize();
      var ticket_post_id = $('#ticket_post_id').val();
      var dept = dept;
      
      if ($('#ticket_web_cro_form').length) {
          $('#ticket_web_cro_form').submit();
      } else {
          revosaveTicket( ticket_post_id,formData, dept) ;
      }
});

function revosaveTicket(ticket_post_id, formData, dept) {
    $.ajax({
      type: "post",
      url: myAjax.url,
      dataType: 'json',
      data: formData + "&ticket_post_id="+ ticket_post_id + "&action=agent_save_ticket&nonce="+ myAjax.nonce,
      success: function(data){ 
              var post_id = data.post_id;
              if (data) {
                  if (dept) {
                    $('#msg-alert-1').html(`Ticket has been successfully assigned to <span class="font-weight-bold">${dept}</span> department`);
                  } else {
                    $('#msg-alert-1').html(`Ticket has been successfully updated! <span class="font-weight-bold" id="display_ticket_number">Ticket no. ${post_id}</span>`);
                  }

                  $('html, body').animate({
                  scrollTop: $('#ticket_detail').offset().top-100
                  }, 800);

                  $('#msg-alert-1').fadeIn();
                  var ticket_number = data.ticket_number;
                  var post_id = data.post_id;
                  
                  $('#ticket_number').val(ticket_number);
                  $('#revo_save_ticket').attr('disabled', false);
                  $('#start_ticket_upload').trigger( "click" );

                // if (dept) {
                    $('#ticket_details_row').hide();
                // }
                  
                  $('#ticketsTab .nav-item a.nav-link.active')[0].click();
                }
              }
  }); 

}

$('html').on('click','#revo_client_save_ticket',function(e) {
  $(this).attr('disabled', true);
  var ticket_post_id = $('#ticket_post_id').val();
  var formData = $('#form_client_documents' ).serialize();
        $.ajax({
          type: "post",
          url: myAjax.url,
          dataType: 'json',
          data: formData+"&ticket_post_id="+ ticket_post_id + "&client_upload_files=true&action=client_save_ticket&nonce="+ myAjax.nonce,
          success: function(data){ //so, if data is retrieved, store it in html
            if (data) {
                  var ticket_number = data.ticket_number;
                  var post_id = data.post_id;
                  $('#ticket_number').val(ticket_number);
                  $('#start_ticket_upload').trigger( "click" );
                  $('#display_ticket_number').html('Ticket no.' + post_id);

                  $('html, body').animate({
                    scrollTop: $('#ticket_detail').offset().top-100
                  }, 800);

                  $('#msg-alert-2').fadeIn();
                  $('#revo_client_save_ticket').attr('disabled', false);
    
                  $('#ticket_details_row').hide();
                  $('.client_nav .nav-item a.nav-link')[0].click();
                  

                 
              }
            }
          });  
});


/* ADD NEW TICKET */
$('html').on('click','.add_ticket_btn',function(e) {
  e.preventDefault();
  $('#ticket_detail').hide();
  $('#msg-alert-1').hide();
  $('#upload-documents-row').removeClass('dropzone-hide');
  $('.file_upload_list tbody').html(`<tr>
  <td colspan="2">No documents</td>
</tr>`);
  $('#add_new_ticket').show();
  $('html, body').animate({
    scrollTop: $('#add_new_ticket').offset().top-100
   }, 800);
});



$('html').on('click','#revo_agent_cancel',function(e) {
  e.preventDefault();
  $('#add_new_ticket').slideUp();
  $('html, body').animate({
    scrollTop: $('#main-content').offset().top-100
   }, 800);

});

$('html').on('click','#client_new_account',function(e) {
  e.preventDefault();
  $('#client_login_form_wrap').hide();
  $('#client_registration_form_wrap').fadeIn();
});

$('html').on('click','#client_login',function(e) {
  e.preventDefault();
  $('#client_login_form_wrap').fadeIn();
  $('#client_registration_form_wrap').hide();
});


$('html').on('click', '#ticketsTab .nav-item .nav-link',function(e,paged) {
  e.preventDefault();
  if(typeof paged == 'undefined'){
    var paged =1;
  }

  $('#ticketsTab .nav-item .nav-link').removeClass('active');
  var agent_id = $(this).attr('rel');
  var agent_department = $(this).attr('data-id');
  $(this).addClass('active');
  load_agent_tickets(agent_id,agent_department,paged);

});

$('html').on('click','#revo_cancel',function(e) {
    e.preventDefault();
    $('#ticket_detail').slideUp();
    $('html, body').animate({
      scrollTop: $('#main-content').offset().top-100
     }, 800);

});

if ($('#ticketsTab').length) {
  $('#ticketsTab .nav-hasaccess a.nav-link')[0].click();
}

/* $('html').on('click', '.revo-file-approve',function(e) {
    e.preventDefault();
    $(this).parent().parent().find('.file-approved').prop('checked', true);
    $(this).parent().parent().find('.file-disapproved').prop('checked', false);
    $(this).parent().parent().find('.file-status-text').text('Approved');
   
});

$('html').on('click', '.revo-file-disapprove',function(e) {
  e.preventDefault();
  $(this).parent().parent().find('.file-approved').prop('checked', false);
  $(this).parent().parent().find('.file-disapproved').prop('checked', true);
  $(this).parent().parent().find('.file-status-text').text('Disapproved');
 
});*/

/* APPROVE/DISAPPROVE DOCUMENTS */
$('html').on('change', '.file_status',function(e) {
    if ($(this).val() == 'approved') {
      $(this).parent().parent().removeClass('file-status-disapproved-c');
      $(this).parent().parent().removeClass('file-status-replaced-c');
      $(this).parent().parent().removeClass('file-status-approved-c');
      $(this).parent().parent().addClass('file-status-approved-c');
    }
    
    if ($(this).val() == 'disapproved') {
      $(this).parent().parent().removeClass('file-status-disapproved-c');
      $(this).parent().parent().removeClass('file-status-replaced-c');
      $(this).parent().parent().removeClass('file-status-approved-c');
      $(this).parent().parent().addClass('file-status-disapproved-c');
    }

    /* DISABLE DEPARTMENT OPTION IF THERE'S DISAPPROVED DOCUMENT */
    if ($('#agent_department_option').length) {
      if($('.file-status-disapproved-c').length) {
          $('#revo_move_ticket, #agent_department_option').attr('disabled', true);
      } else {
        $('#revo_move_ticket, #agent_department_option').attr('disabled', false);
      }
    }


});
  

/* FREETIME */

$('html').on('change', '#ticket_freetime',function(e) {
    $('.freetime_fields').hide();
    if ($(this).val() == 'detention_demurrage') {
        $('#detention_demurrage').show();
    } else if($(this).val() == 'combined') {
      $('#combined').show();
    }
});

   /* LOG IN CLIENT */
   $('#revo-login-form').submit(function( event ) {
    event.preventDefault();
    //$(this).find('button').attr('disabled', true);
      $.ajax({
        type: "post",
        url: myAjax.url,
        dataType: 'json',
        data: $('#revo-login-form').serialize() + "&action=login_user&nonce="+ myAjax.nonce,
        success: function(data){ //so, if data is retrieved, store it in html
            if (data.success) {
              window.location = '/online-processing/';
            } else {
              $('.login-msg').hide();
              if (data.errors) {
                 var errors = data.errors.message;
                 $('.login-msg').html(data.errors.message);
                 $('.login-msg').fadeIn();
              } 
            }
        }
      }); 
  });

  $('html').on('click','.action_view',function(event) {
        event.preventDefault();
        var ticket_id = $(this).attr("data-id");
        var client_ticket = 0;
        
        if ($(this).hasClass('client-ticket')) {
          client_ticket = 1;
        }

        $.ajax({
          type: "post",
          url: myAjax.url,
          data:  "ticket_id="+ ticket_id +"&client_ticket="+ client_ticket +"&action=get_client_ticket&nonce="+ myAjax.nonce,
          success: function(response){  
                 $('#ticket_detail').html(response);
                 $('#ticket_detail').show();
                 $('#ticket_detail').css('visibility', 'visible'); 
                 $('html, body').animate({
                  scrollTop: $('#ticket_detail').offset().top-100
                 }, 800);
                 get_comments(ticket_id);

                  /* DISABLE DEPARTMENT OPTION IF THERE'S DISAPPROVED DOCUMENT */
                  if ($('#agent_department_option').length) {
                    if($('.file-status-disapproved-c').length) {
                        $('#revo_move_ticket, #agent_department_option').attr('disabled', true);
                    }
                  }

                
          }
      }); 
  });

  /* GET COMMENTS */
  
  function get_comments(post_id) {
      $.ajax({
        type: "post",
        url: myAjax.url,
        data:  "post_id="+ post_id +"&action=get_comments&nonce="+ myAjax.nonce,
        success: function(response){  
              /* LOAD COMMENTS */
              
              $('#comments_list').html(response);

        }
    }); 
  } 


  function load_agent_tickets(agent_id, agent_department,paged) {
    $.ajax({
        type: "post",
        url: myAjax.url,
        data:  "agent_id="+ agent_id +"&agent_department="+ agent_department +"&paged="+paged+"&action=load_agent_tickets&nonce="+ myAjax.nonce,
        success: function(response){  
              /* LOAD TICKETS */
             $('#department-tickets').html(response);
            

        }
    }); 
  }
  

/* AJAX SUBMIT WEB CRO FORM */

$('html').on('submit','#ticket_web_cro_form',function(event,dept) {
  event.preventDefault();
  
  var dept = dept;
  var url = $(this).attr('action');
  $.ajax({
      url: url,
      type: "POST",
      data:  new FormData(this),
      contentType: false,
      cache: false,
      processData:false,
      success: function(data)
      {
        if (!data) {
          
        } else {
          $('#ticket_filename_cro').val(data);
        }

        var formData = $('#form_ticket_details, #form_client_documents,#ticket_web_cro_form' ).serialize();
        var ticket_post_id = $('#ticket_post_id').val();

        revosaveTicket( ticket_post_id,formData,dept) ;
      }
    })
});

/* SUBMIT COMMENT */

$('html').on('submit','#commentform',function(event) {
  event.preventDefault();
  $.ajax({
    type: "post",
    url: myAjax.url,
    dataType: 'json',
    data: $('#commentform').serialize() + "&action=add_comment&nonce="+ myAjax.nonce,
    success: function(data){ //so, if data is retrieved, store it in html
      if (data.success) {
          post_id = data.post_id;
          get_comments(post_id); 
          $('textarea#comment').val('');
        
      }
    }
  }); 
});

  
  /* REGISTER NEW CLIENT */
  $('#revo-register-form').submit(function( event ) {
      event.preventDefault();
      var submit_btn =  $(this).find('button');
      submit_btn.attr('disabled', true);
      $.ajax({
        type: "post",
        url: myAjax.url,
        dataType: 'json',
        data: $('#revo-register-form').serialize() + "&action=register_user&nonce="+ myAjax.nonce,
        success: function(data){ //so, if data is retrieved, store it in html
          if (data.success) {
            $('.register-msg').show();
            $('#revo-register-form').find('input[type=email], input[type=password], input[type=number], input[type=text], textarea').val("");
          } else {
            $('.register-msg').hide();
            $('#revo-register-form').find('input').removeClass('is-invalid');
            if (data.errors) {
              submit_btn.attr('disabled', false);
               $.each(data.errors, function(key,value) {
                  $('#input_'+ key).addClass('is-invalid');
                  $('#input_'+ key).after(
                    `<div class="invalid-feedback">
                      ${value}
                     </div>`
                  );
               }); 
            } 
          }
        }
      }); 
  });

  $('html').on('change','.ticket_type', function(e) {
    if ($(this).val() != 'import') {
        $('.form-seach-bol').hide(); 
        $('.upload_douments_button').attr('disabled', false);
    } else {
      $('.form-seach-bol').show(); 
      $('.upload_douments_button').attr('disabled', true);
      if ($('#bol_number_result').length) {
        if ($('#bol_number_result').val() == 0) {
          $('.upload_douments_button').attr('disabled', true)
        } else {
          $('.upload_douments_button').attr('disabled', false)
        }
      }
    }
  });

  $('html').on('click', '.search_bol_btn', function(e){
    e.preventDefault();
    
    if (!$('#bol_number').val()) {
      $('.form-seach-bol').addClass('was-validated');
      return false;
    }

    if ($(this).attr('id')) {
      
      var bol_number = $('#bol_number').val();
      var client_page = $(this).attr('id');
      $.ajax({
        type: "post",
        url: myAjax.url,
        data: "bol_number="+bol_number+"&client_page="+ client_page + "&action=load_user_page&nonce="+ myAjax.nonce,
        success: function(response){ 
                    if ( client_page  == 'search_bol') {
                      $('#bol_results').html(response);
                      if ($('#bol_number_result').val() == 0) {
                        $('.upload_douments_button').attr('disabled', true);
                      } else {
                        $('.upload_douments_button').attr('disabled', false);
                      }
                    }
                }
      }); 
    }
  });

  /* DOWNLOAD ALL DOCUMENTS */
  $('html').on('click', '.download_documents_button', function(e){
    e.preventDefault();
    var ticket_folder = $(this).attr('data-id');
    var formData = "ticket_folder="+ ticket_folder;
    load_agent_page('download_documents', formData);
  });
  


  $('.client_nav a').on('click', function (e, paged) {
    if(typeof paged == 'undefined'){
      var paged =1;
    }
    e.preventDefault()
    if ($(this).attr('id')) {
      var client_page = $(this).attr('id');
      $.ajax({
        type: "post",
        url: myAjax.url,
        data: "client_page="+ client_page + "&paged="+paged+"&action=load_user_page&nonce="+ myAjax.nonce,
        success: function(response){ 

                    if ( client_page  == 'account_profile') {
                      $('#client_profile').html(response);
                    }

                    if ( client_page  == 'pills-home-tab') {
                      $('#client_tickets').html(response);
                    }

                    $('.client_nav').find('.dropdown-menu').removeClass('show');
                }
       }); 
    }
    $(this).tab('show')
  
  });

  if ($('.client_nav').length) {
    $('.client_nav .nav-item a.nav-link.active')[0].click();
  }

  /* PAGINATION */

$('html').on('click','#client_tickets_view .page-link', function(e) {
  e.preventDefault();
  
  var page = $(this).attr('rel');
  $('.client_nav .nav-item a#pills-home-tab').trigger('click', [page]);
 
});


$('html').on('click','#agent_tickets_view .page-link', function(e) {
  e.preventDefault();
 
  var page = $(this).attr('rel');
  $('#ticketsTab .nav-item a.nav-link.active').trigger('click', [page]);
 
});

$('html').on('click','#bols_view .page-link', function(e) {
  e.preventDefault();
 
  var page = $(this).attr('rel');
  $('.agent_nav .nav-item a#bill_of_lading').trigger('click', [page]);
 
});

$('html').on('click', '.add_bol_btn, .edit_bol_btn, .view_bol_btn, .delete_bol_btn, .view_client_btn', function (e) {
  e.preventDefault()
  if ($(this).attr('id')) {
    var agent_admin_page = $(this).attr('id');
    if ($(this).attr('data-id')) {
      var bol_id = $(this).attr('data-id');
      var formData = "bol_id="+ bol_id;
      if($(this).attr('id') == 'view_client') {
        var client_id = $(this).attr('data-id');
        var formData = "client_id="+ client_id;
      }
      
      if ($(this).attr('id') == 'delete_bol') {
        var r = confirm("Are you sure you want to delete B/L?");
        if (r == true) {
          load_agent_page(agent_admin_page, formData);
        }  
      } else {
        load_agent_page(agent_admin_page, formData);
      }
    } else {
      load_agent_page(agent_admin_page);  
    }
  }

});

  $('.agent_nav a, .add_bol_btn').on('click', function (e, paged) {
    e.preventDefault()
    if(typeof paged == 'undefined'){
      var paged =1;
    }

    var formData = "paged="+paged
    
    if ($(this).attr('id')) {
      var agent_admin_page = $(this).attr('id');
      load_agent_page(agent_admin_page, formData );
    }

    $(this).tab('show')
  
  });

  function load_agent_page(agent_admin_page, formData = '') {
    $.ajax({
      type: "post",
      url: myAjax.url,
      data: formData+"&agent_admin_page="+ agent_admin_page + "&action=load_user_page&nonce="+ myAjax.nonce,
      success: function(response){ 
                  if ( agent_admin_page  == 'all_clients' || agent_admin_page  == 'for_approval_clients' ) {
                      $('#agent_clients').html(response);
                  }

                  if ( agent_admin_page  == 'account_profile') {
                    $('#agent_profile').html(response);
                  }

                  if ( agent_admin_page  == 'bill_of_lading') {
                    $('#agent_bol').html(response);
                  }

                  if ( agent_admin_page  == 'add_new_bol' || agent_admin_page  == 'edit_bol'  ) {
                    $('#bol_upload').html(response);
                  }

                  if ( agent_admin_page  == 'delete_bol' ) {
                      $('#bol_upload').html('');
                      $('#agent_bol').html(response);
                  }

                  if ( agent_admin_page  == 'view_bol') {
                    $('#bolModal').modal({
                      backdrop: false,
                      show: false
                    });
                    
                    $('#bolModal').on('hidden.bs.modal', function (e) {
                      $('.modal-backdrop2').hide();
                      $('.modal-backdrop2').css('opacity', '0');
                      $('#top-header').show();
                      $('#main-header').show();
                    })

                    $('#bolModal .modal-body').html(response);
                    $('.modal-backdrop2').show();
                    $('.modal-backdrop2').css('opacity', '0.6');
                    $('#bolModal').modal('show');
                    $('#top-header').hide();
                    $('#main-header').hide();
                    
                  }

                  if ( agent_admin_page  == 'bol_add_submit' ) {
                    var IS_JSON = true;
                    try
                    {
                        var json = $.parseJSON(response);
                    }
                    catch(err)
                    {
                            IS_JSON = false;
                    }                

                    if ( IS_JSON ) {
                      $('#bol-msg-alert-1').html(json.error);
                      $('#bol-msg-alert-1').show();
                    } else {
                      $('#bol_upload').html('');
                      $('#agent_bol').html(response);
                    }
                  }

                  if ( agent_admin_page  == 'download_documents') {
                      var IS_JSON = true;
                      try
                      {
                          var json = $.parseJSON(response);
                      }
                      catch(err)
                      {
                              IS_JSON = false;
                      }                
                      console.log(json.zip_url);
                      if ( IS_JSON ) {
                        var zip_url = json.zip_url;
                        window.open(zip_url);
                      } 
                     
                  }

                  if ( agent_admin_page  == 'view_client') {
                  
                    
                    $('#clientModal').on('hidden.bs.modal', function (e) {
                      $('.modal-backdrop2').hide();
                      $('.modal-backdrop2').css('opacity', '0');
                      $('#top-header').show();
                      $('#main-header').show();
                    })

                    $('#clientModal .modal-body').html(response);

                    $('#clientModal').modal({
                      backdrop: false,
                      show: false
                    });
                   
                    
                    $('#approve_client_btn').attr('data-id', $('#client_id_t').val());
                    $('#deny_client_btn').attr('data-id', $('#client_id_t').val());


                    $('.modal-backdrop2').show();
                    $('.modal-backdrop2').css('opacity', '0.6');
                    $('#clientModal').modal('show');
                    $('#top-header').hide();
                    $('#main-header').hide();
                  }

                  $('.agent_nav').find('.dropdown-menu').removeClass('show');
              }
     }); 
  }

  //APPROVE DENY CLIENT

  $('html').on('click', '.approve_client, .deny_client',function (event) {
    var client_id = $(this).attr('data-id');
    var action_btn= 'approve';
    if ($(this).hasClass('deny_client')) {
      action_btn= 'deny';
    }

    $.ajax({
      type: "post",
      url: myAjax.url,
      data: "action=agent_client_action&client_id="+ client_id +"&action_btn="+action_btn+"&nonce="+ myAjax.nonce,
      success: function(data){ 
                 $('.agent_nav .nav-item #all_clients').click();
                 $('#clientModal').modal('hide');
              }
     }); 

  });
  /* UPLOAD XML FILE */
  $('html').on('click', '#btnSubmit',function (event) {
    event.preventDefault();
    var form = $('#fileUploadForm')[0];
    var data = new FormData(form);
    data.append("action", "upload_xml_file");
    data.append("nonce", myAjax.nonce);
    
    $("#btnSubmit").prop("disabled", true);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: myAjax.url,
        dataType: 'json',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {
            if (data) {
              console.log(data);
              $('#bol_number').val(data.Identification_segment.Bol_reference);
              $('#registry_number').val(data.Identification_segment.Registry_number);
              $('#cos_code').val(data.Identification_segment.Customs_office_segment.Code);
              $('#total_number_of_containers').val(data.Bol_specific_segment.Total_number_of_containers);
              $('#total_gross_mass_manifested').val(data.Bol_specific_segment.Total_gross_mass_manifested);
              $('#volume_in_cubic_meters').val(data.Bol_specific_segment.Volume_in_cubic_meters);
              $('#name_unsigne_content').val(data.Identification_segment.Customs_office_segment.Code);
              $('#bts_code').val(data.Bol_specific_segment.Bol_type_segment.Code);
              $('#number_of_packages').val(data.Bol_specific_segment.Packages_segment.Number_of_packages);
              $('#package_type_code').val(data.Bol_specific_segment.Packages_segment.Package_type_code);
              $('#container_number').val(data.Container.Number);
              $('#container_type').val(data.Container.Type);
              $('#place_of_unloading_segment').val(data.Bol_specific_segment.Place_of_unloading_segment.Code);
            }
            
            $("#btnSubmit").prop("disabled", false);

        },
        error: function (e) {

            $("#result").text(e.responseText);
            console.log("ERROR : ", e);
            $("#btnSubmit").prop("disabled", false);

        }
    });

});

/* ADD NEW BOL */

$('html').on('submit','#cargo_info_form', function(event) {
  event.preventDefault();
  load_agent_page('bol_add_submit', $('#cargo_info_form').serialize());
});

$('html').on('click','#upload_xml_cancel', function(event) {
    $('#bol_upload').html('');
    $('html, body').animate({
      scrollTop: $('#pills-tabContent').offset().top-100
    }, 800);
});


/* FREETIME */

$('html').on('change', '#freetime_date',function (event) {
  var today = new Date();
  var target_date = $(this).val(); 
  var today = new Date();
  var target_date = new Date(target_date); 
  var Difference_In_Time = target_date.getTime() - today.getTime(); 
  var Difference_In_Days = Math.round(Difference_In_Time / (1000 * 3600 * 24)); 
  $('#freetime_no_of_days_d').val(Difference_In_Days);
});


/* SUBMIT TICKET TO DEPT */

$('html').on('click', '#revo_move_ticket',function (event) {
    var dept = $('#agent_department_option').find(":selected").val();
    if (dept == 'Choose Department') {
      alert(' Error: Please choose department!');
    } else {
    var r = confirm("Approve and assign this ticket to "+ dept + " department?");
      if (r == true) {
         $('#ticket_department').val(dept);
         $('#revo_save_ticket').trigger('click', [dept]);
      }  
    }
    
});

/* WEB CRO */

$('html').on('click', '#revo_go_web_cro', function(e)  {
    
  if ($('#ticket_web_cro').val() != 'Choose') {
    var url = $('#ticket_web_cro').children("option:selected").val(); 
    window.open(url, '_blank');
  } else {
    alert(' Error: Please choose web cro ');
  }
    
});

var file_upload_preview_container = '.file_upload_list tbody';

$('html').on('click', '.upload_douments_button', function(e)  {
  e.preventDefault();
  
  $('.modal-backdrop2').show();
  $('.modal-backdrop2').css('opacity', '0.6');
  $('#uploadModalCenter').modal('show');
  $('#top-header').hide();
  $('#main-header').hide();
 
  if($(this).hasClass('ticket-edit')) {
    file_upload_preview_container = '#form_client_documents table tbody';
  } else if ($(this).hasClass('replace-file')) {
     
    file_upload_preview_container = $(this).parent().parent();
  
  }
});

$('html').on('click', '#modal_add_btn', function(e)  {
    var file_rows = $('#previews').find('.file-row');
    
    var file_upload_list_content ='';
    var num_uploaded_files = $('.document_uploaded_filename').length + 1;
    file_rows.each(function( index ) {
    var file_upload_name = $(this).find('p.name').text();
    var file_upload_size = $(this).find('p.size').text();
    if (file_upload_preview_container == '.file_upload_list tbody' ) {
      file_upload_list_content += `<tr>
      <td>
      <input type="hidden" name="ticket_documents[${index+1}][filename]" value="${file_upload_name}" />
      <input type="hidden" name="ticket_documents[${index+1}][file_status]" value="new" />
      <input type="hidden" name="ticket_documents[${index+1}][file_remarks]" value="" />${file_upload_name}</td><td>${file_upload_size}
      </td></tr>`;
    } else {
      if ($(file_upload_preview_container).hasClass('document_row')) {
        
        var file_number = $(file_upload_preview_container).find('input.document_number').val();
        var filestatus = $(file_upload_preview_container).find('.file_status').val();
        var fileremarks = $(file_upload_preview_container).find('.file_remarks').val();
        var filetobedeleted = $(file_upload_preview_container).find('input.document_filename').val();
        
        file_upload_list_content = `
        <tr class="file-not-uploaded">
        <td>
        ${file_number}
        <input type="hidden" name="ticket_documents[${file_number}][filename]" value="${file_upload_name}" />
        <input type="hidden" name="ticket_documents[${file_number}][filetobedeleted]" value="${filetobedeleted}" />
        </td>
        <td>${file_upload_name}</td>
        <td></td>
        <td class="text-center">
            <select name="ticket_documents[${file_number}][file_status]" class="file_status">
                <option value="">Choose</option>`;
              if (filestatus == 'approved') {
                file_upload_list_content += `<option value="approved" selected>Approved</option>
                <option value="disapproved">Disapproved</option>`;
              } else {
                file_upload_list_content += `<option value="approved">Approved</option>
                <option value="disapproved" selected>Disapproved</option>`;
              }
      
        file_upload_list_content += `</select>
        </td>
        <td>
          <textarea class="file_remarks" name="ticket_documents[${file_number}][file_remarks]">${fileremarks}</textarea>
        </td>
        <td></td>
        </tr>`;
    
      } else if ($(file_upload_preview_container).hasClass('file-status-disapproved-c')) {
         /* REPLACE DISAPPROVED DOCUMENT */
         var file_number = $(file_upload_preview_container).find('input.document_number').val();
         var filetobedeleted = $(file_upload_preview_container).find('input.document_filename').val();
        file_upload_list_content = `<tr class="file-not-uploaded"><td><input type="hidden" name="ticket_documents[${file_number}][filename]" value="${file_upload_name}" /><input type="hidden" name="ticket_documents[${file_number}][filetobedeleted]" value="${filetobedeleted}" /><input type="hidden" name="ticket_documents[${file_number}][file_status]" value="replaced" />  ${file_number}</td><td>${file_upload_name}</td><td></td><td></td><td></td><td></td></tr>`;
      } else {
        file_upload_list_content += `<tr class="file-not-uploaded"><td><input type="hidden" name="ticket_documents[${index+num_uploaded_files}][filename]" value="${file_upload_name}"  /><input type="hidden" name="ticket_documents[${index+num_uploaded_files}][file_status]" value="new" /><input type="hidden" name="ticket_documents[${index+num_uploaded_files}][file_remarks]" value="" />${index + num_uploaded_files}</td><td>${file_upload_name}</td><td></td><td></td><td></td><td></td></tr>`;
      }
    }
  });

  if (file_upload_preview_container == '.file_upload_list tbody' ) {
     $(file_upload_preview_container).html(file_upload_list_content);
  } else {
     if ($(file_upload_preview_container).hasClass('file-status-disapproved-c') || $(file_upload_preview_container).hasClass('document_row')) {
        file_upload_list_content1 = `<tr class="file-not-uploaded"><td colspan="6" class="alert alert-warning">Please click save to upload the new document.</td></tr>`;
        $(file_upload_preview_container).replaceWith(file_upload_list_content1 + file_upload_list_content);
     } else {
     $( ".file-not-uploaded " ).remove();
     $(file_upload_preview_container).append(`<tr class="file-not-uploaded"><td colspan="6" class="alert alert-warning">Please click save to upload the following files</td></tr>`);
     $(file_upload_preview_container).append(file_upload_list_content);
     }
  }
 
  $('#modal_close_btn').click();
});

$('#uploadModalCenter').on('hidden.bs.modal', function (e) {
  $('.modal-backdrop2').hide();
  $('.modal-backdrop2').css('opacity', '0');
  $('#top-header').show();
  $('#main-header').show();
})

$('#uploadModalCenter, #bolModal').modal({
  backdrop: false,
  show: false
});


  var $loading = $('#loadingDiv').hide();
  $(document)
  .ajaxStart(function () {
      $loading.show();
      
  })
  .ajaxComplete(function () {
        $loading.hide();
  });

 
});
