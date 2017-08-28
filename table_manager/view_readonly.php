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
            max-height: 80vh;
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
    <?php
        $convert = array(
            'constant'      => 'Constants',
            'logs'          => 'Logs',
            'market_value'  => 'Market Value',
            'material_cost' => 'Material Cost',
            'municipality'  => 'Municipality',
            'municipality_zone' => 'Municipality (4.1-1)',
            'survey'        => 'Survey',
            'User'          => 'User Accounts',
        );
    ?>
    <a class="button primary" href="/index.php">Back</a>
    <div class="row">
        <h3><?=$class->page_name?></h3>
        <div class="table data-list">
            <table>
                <thead>
                    <tr>
                    <?php foreach ($class->columns as $cKey => $cVal) { if ($cVal[0] == "1") { ?>
                        <td><?=$cVal[1]?></td>
                    <?php }} ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($class->data as $row) { ?>
                        <tr>
                        <?php foreach ($class->columns as $cKey => $cVal) { if ($cVal[0] == "1") { ?>
                            <td><?=($cKey == 'table_affected' ? $convert[$row[$cKey]] : $row[$cKey])?></td>
                        <?php }} ?>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        
    </div>

</body>
</html>