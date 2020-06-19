jQuery(function($){
    $('#makan_tunjangan').autoNumeric('init');
    $('#kesehatan_tunjangan').autoNumeric('init');
    $('#produk_tarif').autoNumeric('init');
    $('#hari_tarif').autoNumeric('init');

    $('#status').change(function(){
        var status = $('#status').val();
        if(status == "Kontrak")
        {
            $('#hari_tarif').prop("readonly", true);
            $('#kesehatan_tunjangan').prop("readonly", true);
            $('#hari_tarif').autoNumeric('set', 0);
            $('#kesehatan_tunjangan').autoNumeric('set', 0);
        }
        else{
            $('#hari_tarif').prop("readonly", false);
            $('#kesehatan_tunjangan').prop("readonly", false);
            $('#hari_tarif').val('');
            $('#kesehatan_tunjangan').val('');
        }
    });
          
  });