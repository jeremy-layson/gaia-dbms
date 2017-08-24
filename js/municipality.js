$(document).ready(function(){

    $(".mun-restore").on('click', function(){
        var ans = prompt("Type yes to confirm restoring this item").toUpperCase().trim();
        if (ans === "YES") {
            var me = $(this);
            var id = $(this).attr('data-id');
            //send data to delete.php
            var link = "/municipality/restore.php";
            if (mode == 'zone') {
                link = "/zone/restore.php";
            }
            $.post(link, 
                {id: id},
                function(data) {
                    if (data !== false) {
                        //remove row
                        $(me).parent().parent().remove();
                    }
                }
            );
        }   
    });
    $(".mun-delete").on('click', function(){
        var caption = $(this).html();
        var row = $(this).parent().parent();
        if (caption == 'Cancel') {
            //revert all
            $(".mun-delete").removeAttr('disabled');
            $(".mun-edit").removeAttr('disabled');
            $(this).html('Remove');
            $(this).parent().find('.mun-edit').html('Modify');

            //remove inputs
            var cells = $(row).find('td');
            $(cells[0]).html($(cells[0]).find('input').val());
            $(cells[1]).html($(cells[1]).find('input').val());
            $(cells[2]).html($(cells[2]).find('input').val());
            
        } else {
            var ans = prompt("Type yes to confirm deleting this item").toUpperCase().trim();
            if (ans === "YES") {
                var me = $(this);
                var id = $(this).attr('data-id');
                //send data to delete.php
                var link = "/municipality/delete.php";
                if (mode == 'zone') {
                    link = "/zone/delete.php";
                }
                $.post(link, 
                    {id: id},
                    function(data) {
                        if (data !== false) {
                            //remove row
                            $(me).parent().parent().remove();
                        }
                    }
                );
            }
        }
    });

    $(".mun-edit").on('click', function(){
        //if Modify then change to Save
        //disable all buttons
        var caption = $(this).html();

        if (caption == "Modify") {
            $(".mun-delete").attr('disabled', 'disabled');
            $(".mun-edit").attr('disabled', 'disabled');
            $(this).removeAttr('disabled');
            $(this).html('Save');
            $(this).parent().find('.mun-delete').html('Cancel');            
            $(this).parent().find('.mun-delete').removeAttr('disabled');
            //turn tds into textboxes
            var parent = $(this).parent().parent();

            $(parent).find('td').each(function(obj){
                var txt = $(this).html();
                if (txt.indexOf("<button") === -1) {
                    $(this).html("<input type='text' value='" + txt + "'>");
                }
            });
        } else {
            //send to edit.php
            var row = $(this).parent().parent();
            //get all three
            var mun = $($(row).find('input')[0]).val();
            var brgy = $($(row).find('input')[1]).val();
            var wcard = $($(row).find('input')[2]).val();
            var id = $(this).attr('data-id');

            var link = "/zone/edit.php";
                if (mode == 'zone') {
                    link = "/zone/edit.php";
                }
            $.post(link, 
                {id: id, municipality: mun, baranggay: brgy, wildcard: wcard},
                function(data) {
                    if (data !== false) {
                        //modify row
                        var data = JSON.parse(data);
                        var cells = $(row).find('td');
                        $(cells[0]).html(data[0]);
                        $(cells[1]).html(data[1]);
                        $(cells[2]).html(data[2]);
                        $(row).find('.mun-delete').click();
                    }
                }
            );
        }
    });
});