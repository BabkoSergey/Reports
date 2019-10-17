/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setCookie(name, value, days) {
    var expires = '';

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }

    document.cookie = name + "=" + value + expires + "; path=/";
}


function codefy(text) {
    text = rus_to_latin(text);
    return text.toString().toLowerCase()
            .replace(/\s+/g, '_')           // Replace spaces with _
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '_')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
}

function rus_to_latin(str) {

    var ru = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
        'е': 'e', 'ё': 'e', 'ж': 'j', 'з': 'z', 'и': 'i',
        'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
        'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u',
        'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch', 'ш': 'sh',
        'щ': 'shch', 'ы': 'y', 'э': 'e', 'ю': 'u', 'я': 'ya'
    }, n_str = [];

    str = str.replace(/[ъь]+/g, '').replace(/й/g, 'i');

    for (var i = 0; i < str.length; ++i) {
        n_str.push(
                ru[ str[i] ]
                || ru[ str[i].toLowerCase() ] == undefined && str[i]
                || ru[ str[i].toLowerCase() ].replace(/^(.)/, function (match) {
            return match.toUpperCase()
        })
                );
    }

    return n_str.join('');
}

function checkDec(el, scale=2) {    
    var RE = new RegExp('^\\d*\\.?\\d{0,' + scale + '}$');
    
    if (!RE.test(el))
        el = el.substring(0, el.length - 1);

    return el;
}

function checkReqireFields(form) {    
    if(!form.length > 0) return false;
    
    var isValid = true;

    form.find('input, textarea, select').each(function () {
        if ($(this).prop('required') && !$(this).val()) {
            $(this).addClass('field-error');
            isValid = false;
        }
    });

    return isValid;
}

function ClearCheckReqireFields() {  
    $('input, textarea, select').each(function () {
        $(this).removeClass('field-error');
    });
}

function AddJsNotifi(type, title, message){
    $(function () {  
        var indexBlock, labelBlock, messageBlock;
        
        indexBlock = new Date().getTime();
        labelBlock = (type == 'danger') ? 'warning' : 'check';
        
        messageBlock = '<div id="alert-'+indexBlock+'" class="alert alert-'+type+' alert-dismissible" style="display: none;">';
            messageBlock += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            messageBlock += '<h4><i class="icon fa fa-'+labelBlock+'"></i> '+title+'</h4> '+message;        
        messageBlock += '</div>';
        
        $('.js-notifi').append(messageBlock);
        $('#alert-'+indexBlock).slideDown('slow');
        
        setTimeout(function(){
            $('#alert-'+indexBlock).slideUp('slow');
            setTimeout(function(){
                $('#alert-'+indexBlock).remove();
            }, 500);
        }, 2000);
    });
        
}

$(function () {  
    $(document).on('click', '.btn-collapsed-box-collapse', function(e){
        e.preventDefault();
        var box = $(this).closest('.box');
                
        if(box.hasClass('collapsed-box')){
            box.find('.box-body').show(300);
            box.find('.box-footer').show(300);
        }else{
            box.find('.box-body').hide(300);
            box.find('.box-footer').hide(300);
        }
        box.toggleClass('collapsed-box');
        $(this).find('i').toggleClass('fa-plus fa-minus');
    });
        
    $(document).on('change, keyup', 'input, textarea, select', function (e) {
        $(this).removeClass('field-error');
    });
            
});