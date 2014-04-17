$('#new').click(function(event) {
  event.preventDefault();
  $('#checkModal').modal({
    show: true,
    remote: "v/i"
  });
});

var xhr;
var pay_method, $pay_method;
var pay_id, $pay_id;

$pay_method = $("select[name='p_m']").selectize({
  onChange: function(value) {
    if (!value.length) return;
    pay_id.disable();
    pay_id.clearOptions();
    pay_id.load(function(callback) {
      if (value == 'c') {
        var sid = $("select[name='sID'] option:selected").val(),
        holder = $("select[name='holder'] option:selected").val(),
        data = {m:value,h:holder,s:sid};
      } else if (value == 'h') {
        var money = $("input[name='money']").val(),
        date = $("input[name='invoicedate']").val(),
        data = {m:value,y:money,d:date};
      }
      xhr && xhr.abort();
      xhr = $.ajax({
        url: 'v/p',
        data: data,
        dataType: 'json',
        success: function(results) {
          pay_id.enable();
          callback(results);
        },
        error: function() {
          callback();
        }
      });
    });
  }
});

$pay_id = $("select[name='p_id']").selectize();
pay_method = $pay_method[0].selectize;
pay_id = $pay_id[0].selectize;

pay_id.disable();

$("select").selectize();