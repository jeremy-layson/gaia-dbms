<!DOCTYPE html>
<html>
<head>
    <title><?=$class->page_name?></title>
    <link rel="stylesheet" type="text/css" href="/css/foundation.min.css">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/foundation.min.js"></script>
    <!-- <script type="text/javascript" src="/js/table-manager.js"></script> -->
    <style type="text/css">
        .table, .deleted {
            height: 80vh;
            overflow: scroll;
        }

        .form {
            border: 1px solid #e3e3e3;
            padding: 15px;
        }
        table {
            border: 1px solid #e3e3e3;
        }
        table td {
            white-space: nowrap;
            margin: 0px;
            padding: 0px;
        }

        table td button {
            margin: 0px !important;
            padding: 0px;
        }
    </style>
</head>
<body>
    <a class="button primary" href="/index.php">Back</a>
    <div class="row">
        <h3><?=$class->page_name?></h3>
        <div class="table data-list">
            <table>
                <thead>
                    <tr>
                    <td>Actions</td>
                    <?php foreach ($class->columns as $cKey => $cVal) { if ($cVal[0] == "1") { ?>
                        <td><?=$cVal[1]?></td>
                    <?php }} ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($class->data as $row) { ?>
                        <tr>
                        <td>
                            <button class="button primary form-edit" data-id="<?=$row['uid']?>">Edit</button>
                            <button class="button warning form-delete" data-id="<?=$row['uid']?>">Remove</button>
                        </td>
                        <?php foreach ($class->columns as $cKey => $cVal) { if ($cVal[0] == "1") { ?>
                            <td><?=$row[$cKey]?></td>
                        <?php }} ?>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- FORM -->
        <div class="form">
            <form action="/" method="POST">
                <h3>Create new</h3>
                <div class="row">
                <?php $nCtr = 0;foreach ($class->columns as $cKey => $cVal) { if ($cVal[0] == "1") { $nCtr++;?>
                    <div class="large-3 column">
                        <label for="<?=$cKey?>"><?=$cVal[1]?></label>
                    </div>
                    <div class="large-3 column">
                        <input type="text" name="<?=$cKey?>" id="<?=$cKey?>">
                    </div>
                <?php }} 
                    if ($nCtr % 2 === 1) {
                ?>
                    <div class="large-3 column"></div>
                    <div class="large-3 column"></div>
                <?php
                    }
                ?>
                </div>
                <div class="row">
                    <div class="large-3 column">
                        &nbsp;
                        <input type="hidden" name="uid" id="uid">
                    </div>
                    <div class="large-9 column">
                        <input type="submit" value="Create New" class="button primary form-submit">
                        <button type="button" class="button warning form-reset">Reset</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- END OF FORM -->
        <div class="deleted">
            <h3>Deleted Records</h3>
            <table>
                <thead>
                    <tr>
                    <td>Actions</td>
                    <?php foreach ($class->columns as $cKey => $cVal) { if ($cVal[0] == "1") { ?>
                        <td><?=$cVal[1]?></td>
                    <?php }} ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($class->deleted as $row) { ?>
                        <tr>
                        <td>
                            <button class="button warning form-restore" data-id="<?=$row['uid']?>">Restore</button>
                        </td>
                        <?php foreach ($class->columns as $cKey => $cVal) { if ($cVal[0] == "1") { ?>
                            <td><?=$row[$cKey]?></td>
                        <?php }} ?>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        var table = "<?=$class->table_name?>";
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
                            {id: id, table: table},
                            function(data) {
                                location.reload();
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
                        $.post("/table_manager/delete.php", 
                            {id: id, table: table},
                            function(data) {
                                location.reload();
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
                    $.post("/table_manager/edit.php", 
                        {data: data, table: table},
                        function(data) {
                            location.reload();
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
                    $.post("/table_manager/create.php", 
                        {data: data, table: table},
                        function(data) {
                            location.reload();
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
    </script>
</body>
</html>