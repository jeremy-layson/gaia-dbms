<!DOCTYPE html>
<html>
<head>
    <title>Survey Management</title>
    <link rel="stylesheet" type="text/css" href="/css/foundation.min.css">
    <link rel="stylesheet" type="text/css" href="/css/survey.css">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/foundation.min.js"></script>
    <script type="text/javascript" src="/js/survey.js"></script>
    
    <script type="data/javascript" id="survey-data">
    <?php 
        //retrieve all data
        include_once('../sql.php');
        $result = $link->query("SELECT * FROM survey");
        $columns = $link->query("show full columns from survey");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $column = [];
            foreach ($row as $key => $col) {
                $column[] = array($key, $col);
            }
            $data[] = $column;
        }
        echo json_encode($data);
    ?>
    </script>
    <script type="template/javascript" id="buttons-template">
        <td>
            <button class="button primary survey-edit" data-id="{id}">Edit</button>
            <button class="button warning survey-delete" data-id="{id}">Remove</button>
        </td>
    </script>
</head>
<body>
    <div class="container row">
        <div class="large-12">
            <h3>Manage Data</h3>
        </div>
        <div class="data-list">
            <table>
                <thead>
                    <tr>
                        <?php 
                            echo "<td>Actions</td>";
                            while ($row = $columns->fetch_assoc()) {
                                $col = explode("|", $row['Comment']);
                                echo "<td>" . $col[1] . "</td>";
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <script type="text/javascript">
                        var data = JSON.parse($('#survey-data').html());
                        var button = $('#buttons-template').html();
                        $.each(data, function(){
                            var new_button = button.replace("{id}", $(this)[0][1]).replace("{id}", $(this)[0][1]);
                            var row = $(this);
                            document.write("<tr>");
                            document.write(new_button);
                            $.each(row, function(){
                                document.write("<td>" + $(this)[1] + "</td>");
                            });
                            document.write("</tr>");
                        });
                    </script>
                </tbody>
            </table>
        </div>

        <div class="form">
            <form action="/" method="POST">
                <h3>Create new</h3>
                <div class="row">
                <?php
                    mysqli_data_seek($columns, 0);
                    while ($row = $columns->fetch_assoc()) {
                        $col = explode("|", $row['Comment']);
                        $field = $row['Field'];
                        $text = $col[1];
                        if ($col[0] == "1") {
                ?>
                    
                        <div class="large-2 column">
                            <label for="<?=$field?>"><?=$text?></label>
                        </div>
                        <div class="large-4 column">
                            <input type="text" name="<?=$field?>" id="<?=$field?>">
                        </div>
                    
                <?php
                        }
                    }
                    if ($columns->num_rows % 2 === 0) {
                ?>
                    <div class="large-2 column">
                        &nbsp;
                    </div>
                    <div class="large-4 column">
                        &nbsp;
                    </div>
                <?php
                    }
                ?>
                </div>
                <div class="row">
                    <div class="large-2 column">
                        &nbsp;
                        <input type="hidden" name="uid" id="uid">
                    </div>
                    <div class="large-10 column">
                        <input type="submit" value="Create New" class="button primary form-submit">
                        <button type="button" class="button warning form-reset">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>