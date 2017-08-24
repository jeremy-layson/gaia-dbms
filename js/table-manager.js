$(document).ready(function(){
    $('.form').find('input[type="text"]').attr('disabled', true);

    $('.data-list').on('click', '.form-edit', function(){
        var id = $(this).attr('data-id');
        var tds = $(this).parent().parent().find('td');
        var forms = $('.form').find('input');

        $('#uid').val(id);

        for (var i=0; i < forms.length-2; i++) {
            forms[i].value = tds[i+1].innerText;
        }
        $('.form').find('input[type="text"]').attr('disabled', false);
        $('.form-submit').val('Update');
        $('.form-reset').html('Cancel');
        $('.form h3').html('Modify existing');
    });

    $('.deleted').on('click', '.form-restore' , function(){
        var ans = prompt("Type yes to confirm restoring this record").toUpperCase().trim();
            if (ans === "YES") {
                var me = $(this);
                var id = $(this).attr('data-id');
                //send data to delete.php
                $.post("/table_manager/restore.php", 
                    {id: id},
                    function(data) {
                        if (data !== false) {
                            location.reload()
                        }
                    }
                );
            }
    });

    $('.data-list').on('click', '.form-delete' , function(){
        var ans = prompt("Type yes to confirm deleting this record").toUpperCase().trim();
            if (ans === "YES") {
                var me = $(this);
                var id = $(this).attr('data-id');
                //send data to delete.php
                $.post("/survey/delete.php", 
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

    $('.form-submit').on('click', function(e){
        e.preventDefault();
        var txt = $(this).val();

        if (txt == 'Update') {
            var inputs = $('.form input[type="text"], input[type="hidden"]');
            var data = {};
            $.each(inputs, function(index, node){
                data[node.name] = node.value;
            });
            $.post("/survey/edit.php", 
                {data: data},
                function(data) {
                    if (data !== false) {
                        var data = JSON.parse(data);
                        var uid = data.uid
                        var row = $($('[data-id=' + uid + ']')[0]).parent().parent().find('td');
                        
                        var ctr = 1;
                        $.each(data, function(index, val){
                            row[ctr].innerHTML = val;
                            ctr++;
                        });

                        //reset
                        $('.form input[type="text"]').val('').attr('disabled', true);
                        $('.form input[type="hidden"]').val('');

                        $('.form-reset').html('Reset');
                        $('.form-submit').val('Create New');
                        $('.form h3').html('Create new');

                    }
                }
            );
        } else if (txt == 'Create New') {
            //add 
            $('.form input[type="text"]').attr('disabled', false);
            $(this).val('Save');
            $('.form-reset').html('Cancel');
        } else {
            //save
            var inputs = $('.form input[type="text"], input[type="hidden"]');
            var data = {};
            $.each(inputs, function(index, node){
                data[node.name] = node.value;
            });
            $.post("/survey/create.php", 
                {data: data},
                function(data) {
                    if (data !== false) {
                        //add new row
                        var data = JSON.parse(data);
                        var uid = data.uid
                        var row = "<tr>";

                        var button = $('#buttons-template').html();
                        var new_button = button.replace("{id}", uid).replace("{id}", uid);
                        row = row + new_button;

                        var ctr = 1;
                        $.each(data, function(index, val){
                            row = row + "<td>" + val + "</td>";
                        });

                        row = row + "</tr>";

                        $('.data-list table tbody').append(row);

                        //reset
                        $('.form input[type="text"]').val('').attr('disabled', true);
                        $('.form input[type="hidden"]').val('');

                        $('.form-reset').html('Reset');
                        $('.form-submit').val('Create New');
                        $('.form h3').html('Create new');

                    }
                }
            );
        }
    });

    $('.form-reset').on('click', function(){
        var txt = $(this).html();
        if (txt == 'Reset') {
            $(this).parent().parent().parent().find('input[type="text"]').val('');
        } else {
            //if Cancel
            $('.form').find('input[type="text"]').attr('disabled', true);
            $(this).parent().parent().parent().find('input[type="text"]').val('');
            $('.form-reset').html('Reset');
            $('.form-submit').val('Create New');
            $('.form h3').html('Create new');
        }
    });

});