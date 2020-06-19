var dataTable;
var urlTable  = 'LoadBeban';
var urlTambah = 'SimpanBeban';
var urlGet    = 'GetBeban/' 
    $(document).ready(function(){
        dataTable = $('#TabelBeban').DataTable({
                    bAutoWidth: false,
                    responsive: true,
                    "processing":true,
                    "serverSide":true,
                    "order":[],
                    "ajax":{
                        url:urlTable,
                        type:"POST"
                    },
                    "columnDefs":[
                        {
                            "targets":[2],
                            "orderable":false,
                        }
                    ],
                });

        $('#tambah').click(function(){
        	$('#ModalBebanTambah').modal('show');
        });

        jQuery.validator.addMethod("lettersonly", function(value, element, param) {
  			return value.match(new RegExp("." + param + "$"));
			});

			$('#TambahBeban').validate({
				rules:{
					"nama_beban":{
						required: true,
						maxlength: 50,
						minlength: 5,
						lettersonly: "[a-zA-Z]+"
					}
				},
				messages:{
					"nama_beban":{
						required: "Nama Beban Tidak Boleh Kosong",
						maxlength: "Nama Beban Maksimal {0} Karakter",
						minlength: "Nama Beban Minimal {0} Karakter",
						lettersonly: "Nama Beban Tidak Mengandung Angka"
					}
				},
			highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        	},
        	unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        	},
        	errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        	},
        	submitHandler: function(form)
        	{
        		$.ajax({
        			url: urlTambah,
        			type: "POST",
        			data: $('#TambahBeban').serialize(),
        			success: function(data){
        				$('#ModalBebanTambah').modal('hide');
        				$('#TambahBeban')[0].reset();
        				if(data == "Data Beban Berhasil Dimasukkan")
        				{
        					const toast = Swal.mixin({
							  toast: true,
							  position: 'top-end',
							  showConfirmButton: false,
							  timer: 5000
							});

							toast({
							  type: 'success',
							  title: data
							});
							dataTable.ajax.reload();
        				}
        				else if(data == "Data Beban Gagal Dimasukkan")
        				{
        					const toast = Swal.mixin({
							  toast: true,
							  position: 'top-end',
							  showConfirmButton: false,
							  timer: 5000
							});

							toast({
							  type: 'error',
							  title: data
							});
        				}
        			}
        		});
        	}
			});

            $('#UpdateBeban').validate({
                rules:{
                    "nama_beban":{
                        required: true,
                        maxlength: 50,
                        minlength: 5,
                        lettersonly: "[a-zA-Z()]+"
                    }
                },
                messages:{
                    "nama_beban":{
                        required: "Nama Beban Tidak Boleh Kosong",
                        maxlength: "Nama Beban Maksimal {0} Karakter",
                        minlength: "Nama Beban Minimal {0} Karakter",
                        lettersonly: "Nama Beban Tidak Mengandung Angka"
                    }
                },
            highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
            },
            submitHandler: function(form)
            {
                $.ajax({
                    url: urlTambah,
                    type: "POST",
                    data: $('#UpdateBeban').serialize(),
                    success: function(data){
                        $('#ModalBebanUpdate').modal('hide');
                        if(data == "Data Beban Berhasil Diubah")
                        {
                            const toast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 5000
                            });

                            toast({
                              type: 'success',
                              title: data
                            });
                            dataTable.ajax.reload();
                        }
                        else if(data == "Data Beban Gagal Diubah")
                        {
                            const toast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 5000
                            });

                            toast({
                              type: 'error',
                              title: data
                            });
                        }
                    }
                });
            }
            });
    });
     function editdata(id_beban)
        {
            $.ajax({
                type: 'GET',
                url: urlGet + id_beban,
                dataType: 'json',
                success: function(beban){
                    $('#ModalBebanUpdate').modal('show');
                    $('#kode_beban').val(beban.id_beban);
                    $('#nama_beban_update').val(beban.nama_beban);
                }
            });
        }   
