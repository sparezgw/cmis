$(".login").submit(function(event) {
  event.preventDefault();
  $.post('user/login', $(this).serialize(), function(data) {
    if(data != "false")
      $("#error").html("<strong>错误！</strong>"+data).show('slow');
    else location.href="/home";  
  });
});

$(".login > input").focus(function() {
  $("#error").hide('2000');
});