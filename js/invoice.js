$('#new').click(function(event) {
  event.preventDefault();
  $('#newModal').modal({
    show: true,
    remote: "v/i"
  });
});
$('#newModal').on('shown.bs.modal', function() {
  $("select").selectize();
})
$('#newModal').on('hide.bs.modal', function() {
  console.log("dfdfd")
})

var $si = $("select[name='items']").selectize({
  hideSelected: true,
  onItemAdd: function(value, item) {
    if (!value.length) return;
    var add = $("#addModal");
    add.find("h4").text(item.text());
    add.find("input#iID").val(value);
    add.modal();
  }
});
$("select").selectize();

$("#addModal").submit(function(event) {
  event.preventDefault();
  var table = $(".table tbody"),
    name = $("h4").text(),
    iid = $("#iID").val(),
    amount = parseInt($("input#amount").val()),
    price = parseFloat($("input#price").val()),
    money = amount*price;
  table.append('<tr><td><a class="text-danger" data-id="'+iid+'"><span class="glyphicon glyphicon-remove"></span></a></td><td>'+name+'</td><td>'+amount+'</td><td>'+price.toFixed(2)+'</td><td>'+money.toFixed(2)+'</td></tr>');
  $("body").data(iid, {a:amount, p:price});
  var total = sum();
  $("input[name='money']").val(total.toFixed(2));
  with($si[0].selectize) {
    removeOption(iid);
    // refreshOptions(true);
  }
  $(this).modal('toggle');
});

$(document).on('click', 'a.text-danger', function() {
  var id = $(this).data("id");
  $(this).parent("td").parent("tr").remove();
  $("body").removeData(id);
  var total = sum();
  with($si[0].selectize) {
    addOption({value:id});
    // refreshOptions(true);
  }
  $("input[name='money']").val(total.toFixed(2));
});

$("form:first").submit(function(event) {
  event.preventDefault();
  $("[name='items'] option:selected").val(JSON.stringify($("body").data()))
  $(this).submit();
});

function sum() {
  var total = 0;
  $.each($("body").data(), function(i, v) {
    total += v.a * v.p;
  });
  return total;
}
