$(document).ready(function()
{
    /////////////////////////////////////////
    ///// carpetas.php
    /////////////////////////////////////////

	$(".open-dropdown span").click(function()
	{
		$(this).parent().next( "ul.dropdown" ).toggleClass('d-none');
        var img = $(this).prev();
        if(img.attr('src') == 'img/folder-close.png')
            img.attr('src','img/folder-open.png');
        else
            img.attr('src','img/folder-close.png');
	});
 	$(".edit").on("click", function()
 	{
 		var edit = $(this);
 		var li = edit.parent();
 		var save = edit.next();
 		var input = edit.prev();
 		var span = input.prev();
 		input.show();
 		span.hide();
 		edit.hide();
 		save.show();
	  });
	$(".save").on("click", function()
	{
 		var save = $(this);
 		var li = save.parent();
 		var edit = save.prev();
 		var input = edit.prev();
 		var span = input.prev();
 		input.hide();
 		span.show();
 		edit.show();
 		save.hide();
 		
 		// Si no se ha modificado, no hacemos nada
 		if(input.val() == span.text())
 		{
 		 	console.log("sin cambios");
 		 	return;
 		}

 		span.text(input.val());


		$.ajax({
			url:'dir_scanner.php',
			type: 'POST',
			dataType: 'json',
			data: {
				'oldName': edit.data('path')+'/'+save.data('path'),
				'newName': edit.data('path')+'/'+span.text()
			},
			success: function (data) {
				console.log(data);
			}
		});
	});

    $(".add").on("click", function()
    {
        $(this).prev().click();
    });

    $(".upload").on("change", function()
    {
        var spinner = $(this).parent().find('.uploadspinner').show();
        var file_data = $('.upload').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        var path = $(this).next().data('path');
        form_data.append('path', path);

        $.ajax({
            url: 'upload.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(respuesta)
            {
                spinner.hide();
                var res = JSON.parse(respuesta);
                if(res.result==1)
                {
                    var alert = $('.alert');
                    alert.prepend('Archivo subido correctamente');
                    alert.removeClass('d-none').addClass('show');

                    setTimeout(function() {
                        alert.removeClass('show').addClass('d-none');
                    }, 3000);
                }
            }
         });
    });

	$("#newdirectory").on("click", function()
	{
        // Modal crear directorio
        var modal = $('#modal');
        modal.modal('show');
        modal.find('.modal-title').text("Crear carpeta");
        modal.find('.modal-body').html(`
            
            <div class="form-group nombreCarpeta">
                <label for="nombreCarpeta">Nombre de la carpeta</label>
                <input type="text" class="form-control" id="nombreCarpeta">
                <button class="btn btn-secondary creaCarpeta">Crear carpeta</button>
            </div>
            <div class="form-group">
                <label for="newfile">Subir archivo</label>
                <input type="file" id="newfile" class="newfile">
                <input type="hidden" id="path">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `);
    });
    

    $(document).on("click", ".creaCarpeta", function()
    {
        var data =
        {
            'newdirectory': $('#nombreCarpeta').val()
        }
        creaCarpeta(data);
    });

    function creaCarpeta(data)
    {
        $.ajax({
            url:'dir_scanner.php',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (data)
            {
                console.log(data);
                if(data.result==1)
                {
                    var carpeta = data.nombreCarpeta;
                    $('#directories').append(`
                        <li class="open-dropdown" id="`+carpeta+`">
                            <img src="img/folder-open.png" class="folder"/>
                            <span>`+carpeta+`</span>
                        </li>
                    `);
                    var li = document.getElementById(carpeta);
                    li.scrollIntoView();
                    $('#modal').modal('hide');
                    $('.alert').alert()
                }
            }
        });
    }

	$(".delete").on("click", function()
	{
	    if (confirm("¿Quieres eliminar el archivo?") === false)
	    {
			return;
	    }
 		var del = $(this);
 		var li = del.parent();

		$.ajax({
			url:'dir_scanner.php',
			type: 'POST',
			dataType: 'json',
			data:
			{
				'deleteFile': del.data('path')
			},
			success: function (data)
			{
				console.log(data);
				if(data==1)
				    li.remove();
			}
		});
	});

});