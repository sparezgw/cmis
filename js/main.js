$(".btnTabDel").click(function() {
  event.preventDefault();

  var id = $(this).attr('data-id');
  var form = $("<form action='/i/"+id+"' method='post'></form>");
  form.append("<input type='hidden' value='DELETE' name='_method'/>");
  form.appendTo(document.body).submit();
});

