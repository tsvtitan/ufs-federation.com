$(document).ready(function() {
  $('#show-policy2').tooltip();
  
  $('#optionsRadios12').change(function(){
    $('#q1-option2').removeClass('hidden').focus();
  });
  $('#optionsRadios32').change(function(){
    $('#q3-option2').removeClass('hidden').focus();
  });
  $('#optionsRadios44').change(function(){
    $('#q4-option4').removeClass('hidden').focus();
  });
  $('#optionsRadios132').change(function(){
    $('#q13-option2').removeClass('hidden').focus();
  });
  /*
  $('#anketa-part1').submit(function(){
    $(this).slideUp(300);
    $('#anketa-part2').slideDown(300,function(){
      $('body').scrollTo($('#anketa-part2').offset().top-20, {duration: 600});
    });
    return false;
  });
  $('#anketa-part2').submit(function(){
    $(this).slideUp(300);
    $('#thanks2, #thanks2extra').slideDown(300,function(){
      $('body').scrollTo($('#thanks2').offset().top-20, {duration: 600});
      
    });
    return false;
  });
  */
  
  /* popup */
  $('#callback').click(function(e){
    var theInput = $('#callback-phone');
    theInput.focus();
    
    e.stopPropagation();
    e.preventDefault();
    
    Avgrund.show('#callback-popup');
    return false;
  });
  $('#callback-popup button').click(function(){
    var phone = $('#callback-phone').val();
    var length = phone.length;
    
    if (length == 11) {
      $('#callback-popup input').prop('disabled', true);
      $('#callback-popup input').addClass('.disabled');
      
      var url='http://ru.ufs-federation.com/callback.html?phone='+phone;
      $.getJSON(url,{format:'json'}).done(function(data) {
        if (data) {
          if (data.success) {
            $('#callback-popup button').text('Соединение...');
          } else {
            $('#callback-popup button').text(data.message);
          }
        }
        //$('#mc-embedded-callback').show();
      });
    } else {
      $('#callback-phone').focus();
    }
    return false;
  });
  
  
  
  
  
  
  
  
  
  
  
  
  
  $('.open-dialog').click(function(e){
    e.stopPropagation();
    e.preventDefault();
    return false;
    
    Avgrund.show('#default-popup');
  });
  $('.close-dialog, .popup-cover').click(function(e){
    e.stopPropagation();
    e.preventDefault();
    Avgrund.hide();
    return false;
  });
  
  $('#q1').mask("99");
  $('#q2').mask("99");
  $('#q3').mask("99");
  $('#q4').mask("999");
  $('#q5').mask("999");
  
  $("#inquirer").validate({
    errorPlacement: function(error, element) {
      //error.appendTo( element.parents("dd") ); /*.next("div") */
    },
    rules: {
      "q1": {
        required: true,
        number: true
      },
      "q2": {
        required: true,
        number: true
      },
      "q3": {
        required: true,
        number: true
      },
      "q4": {
        required: true,
        number: true
      },
      "q5": {
        required: true,
        number: true
      },
      "email": {
        required: true,
        email: true
      }      
    },
    messages: {
      "q1": {
        required: "Пожалуйста, укажите количество золотых медалей",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "q2": {
        required: "Пожалуйста, укажите количество золотых медалей",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "q3": {
        required: "Пожалуйста, укажите количество серебрянных медалей",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "q4": {
        required: "Пожалуйста, укажите количество бронзовых медалей",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "q5": {
        required: "Пожалуйста, укажите доходность модельного портфеля",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "email": {
        required: "Пожалуйста, укажите доходность для инвесторов",
        number: "Пожалуйста, введите только цифры без пробелов"
      }
    }
  });
});














/* EN version




$(document).ready(function() {
  $('#show-policy2').tooltip();
  
  $('#q1').mask("99");
  $('#q2').mask("99");
  $('#q3').mask("99");
  $('#q4').mask("999");
  $('#q5').mask("999");
  
  $("#inquirer").validate({
    errorPlacement: function(error, element) {
      //error.appendTo( element.parents("dd") );
    },
    rules: {
      "q1": {
        required: true,
        number: true
      },
      "q2": {
        required: true,
        number: true
      },
      "q3": {
        required: true,
        number: true
      },
      "q4": {
        required: true,
        number: true
      },
      "q5": {
        required: true,
        number: true
      },
      "email": {
        required: true,
        email: true
      }      
    },
    messages: {
      "q1": {
        required: "Пожалуйста, укажите количество золотых медалей",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "q2": {
        required: "Пожалуйста, укажите количество золотых медалей",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "q3": {
        required: "Пожалуйста, укажите количество серебрянных медалей",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "q4": {
        required: "Пожалуйста, укажите количество бронзовых медалей",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "q5": {
        required: "Пожалуйста, укажите доходность модельного портфеля",
        number: "Пожалуйста, введите только цифры без пробелов"
      },
      "email": {
        required: "Пожалуйста, укажите доходность для инвесторов",
        number: "Пожалуйста, введите только цифры без пробелов"
      }
    }
  });
});
*/