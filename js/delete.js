$("button.btn-danger").click(function() {
  event.preventDefault();
  $("input[name='_method']").attr('value', 'DELETE');
  $("form").submit();
});