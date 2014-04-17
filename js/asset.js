var xhr;
var asset_item, $asset_item;

$("button.btn-info").click(function() {
  event.preventDefault();
  var vno = $("#invoiceno").val();
  if (!vno.length) return;
  asset_item.disable();
  asset_item.clearOptions();
  asset_item.load(function(callback) {
    xhr && xhr.abort();
    xhr = $.ajax({
      url: 'v/i',
      data: {vno:vno},
      dataType: 'json',
      success: function(results) {
        asset_item.enable();
        callback(results);
      },
      error: function() {
        callback();
      }
    });
  });
});

$asset_item = $("select[name='iID']").selectize();
asset_item = $asset_item[0].selectize;

asset_item.disable();