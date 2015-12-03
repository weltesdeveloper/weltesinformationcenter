$(function(){
    //original field values
    var field_values = {
            //id        :  value
            'revisiondate'  : 'revisiondate',
            'headmark'  : 'headmark',
            'quantity' : 'quantity',
            'surface'  : 'surface',
            'blasting'  : 'blasting',
            'primer'  : 'primer',
            'intermediate'  : 'intermediate',
            'finishing'  : 'finishing',
            'subcontid'  : 'subcontid'
    };

    //inputfocus
    $('input#revisiondate').inputfocus({ value: field_values['revisiondate'] });
    $('input#headmark').inputfocus({ value: field_values['headmark'] });
    $('input#quantity').inputfocus({ value: field_values['quantity'] }); 
    $('input#surface').inputfocus({ value: field_values['surface'] });
    $('input#blasting').inputfocus({ value: field_values['blasting'] });
    $('input#primer').inputfocus({ value: field_values['primer'] });
    $('input#intermediate').inputfocus({ value: field_values['intermediate'] });
    $('input#finishing').inputfocus({ value: field_values['finishing'] });
    $('input#subcontid').inputfocus({ value: field_values['subcontid'] }); 

    //reset progress bar
    $('#progress').css('width','0');
    $('#progress_text').html('0% Complete');

    //first_step
    $('form').submit(function(){ return false; });
    $('#submit_first').click(function(){
        //remove classes
        $('#first_step input').removeClass('error').removeClass('valid');

        //ckeck if inputs aren't empty
        var fields = $('#first_step input[type=text], #first_step input[type=password]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
            if( value.length<4 || value==field_values[$(this).attr('id')] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
                
                error++;
            } else {
                $(this).addClass('valid');
            }
        });        
        
        if(!error) {
            if( $('#password').val() != $('#cpassword').val() ) {
                    $('#first_step input[type=password]').each(function(){
                        $(this).removeClass('valid').addClass('error');
                        $(this).effect("shake", { times:3 }, 50);
                    });
                    
                    return false;
            } else {   
                //update progress bar
                $('#progress_text').html('33% Complete');
                $('#progress').css('width','113px');
                
                //slide steps
                $('#first_step').slideUp();
                $('#second_step').slideDown();     
            }               
        } else return false;
    });

    $('#submit_second').click(function(){
        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');

        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
        var fields = $('#second_step input[type=text]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
            if( value.length<1 || value==field_values[$(this).attr('id')] || ( $(this).attr('id')=='email' && !emailPattern.test(value) ) ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
                
                error++;
            } else {
                $(this).addClass('valid');
            }
        });

        if(!error) {
                //update progress bar
                $('#progress_text').html('66% Complete');
                $('#progress').css('width','226px');
                
                //slide steps
                $('#second_step').slideUp();
                $('#third_step').slideDown();     
        } else return false;

    });


    $('#submit_third').click(function(){
        //update progress bar
        $('#progress_text').html('100% Complete');
        $('#progress').css('width','339px');

        //prepare the fourth step
        var fields = new Array
        (
            $('#revisiondate').val(),
            $('#headmark').val(),
            $('#quantity').val(),
            $('#surface').val(),
            $('#blasting').val(),
            $('#primer').val(),
            $('#intermediate').val(),
            $('#finishing').val(),
            $('#subcontid').val()                     
        );
        var tr = $('#fourth_step tr');
        tr.each(function(){
            //alert( fields[$(this).index()] )
            $(this).children('td:nth-child(2)').html(fields[$(this).index()]);
        });
                
        //slide steps
        $('#third_step').slideUp();
        $('#fourth_step').slideDown();            
    });


    $('#submit_fourth').click(function(){
        $inputsql = 'INSERT INTO PAINTING (REV_DATE, HEAD_MARK, QTY, SURFACE, BLASTING, PRIMER, INTERMEDIATE, FINISHING, SUBCONT_ID)\n\
                VALUES (revisiondate, headmark, quantity, surface, blasting, primer, intermediate, finishing, subcontid)';
        $inputparse = OCI_PARSE($conn, $inputsql);
        $inputexecute = OCI_EXECUTE($inputparse);
        
        if(!$inputexecute){
            alert('Date transfer failed');
        } else {
        alert('Data has been sent to the server');
        }
    });
});