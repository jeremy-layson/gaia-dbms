<?php 
    require_once('../zone/municipalityClass.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Municipalities (4.1-1)</title>  
    <script type="text/javascript">
      var mode = "zone";
    </script>
    <link rel="stylesheet" type="text/css" href="/css/foundation.min.css">
    <link rel="stylesheet" type="text/css" href="/css/municipality.css">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/foundation.min.js"></script>
    <script type="text/javascript" src="/js/municipality.js"></script>
</head>
<body>
    <div class="top-header">
        <a href="/" class="button">Home</a>
    </div>
    <div class="main large-12">
       <div class="list large-12">
           <div class="header">
               <h1>Municipalities and Baranggays</h1>
               <div class="note primary label large-12">
                   <b>Information: </b><i>Wildcard is the comma separated accepted value for a specific baranggay.</i>
               </div>
           </div>
           <div class="table-municipality">
               <table>
                   <thead>
                       <tr>
                           <td width="20%">Municipality</td>
                           <td width="20%">Baranggay</td>
                           <td width="40%">Wildcard</td>
                           <td width="20%">Action</td>
                       </tr>
                   </thead>
                   <tbody>
                        <?php
                            $data = $municipality->getAll();
                            foreach ($data as $key => $baranggay) {
                                foreach ($baranggay as $value) {
                                    $brgy = $value[0];
                                    $wildcard = $value[1];
                                    $id = $value[2];
                        ?>
                                    <tr>
                                        <td><?=$key?></td>
                                        <td><?=$brgy?></td>
                                        <td><?=$wildcard?></td>
                                        <td>
                                            <button class="button success mun-edit" data-id="<?=$id?>">Modify</button>
                                            <button class="button alert mun-delete" data-id="<?=$id?>">Remove</button>
                                        </td>
                                    </tr>
                        <?php 
                                }
                            }
                        ?>
                   </tbody>
               </table>
               <div class="municipality-form large-12 row">
                   <form method="POST" action="/zone/add.php">
                        <div class="large-3 column"><input type="text" name="municipality" placeholder="Municipality"></div>
                        <div class="large-3 column"><input type="text" name="baranggay" placeholder="Baranggay"></div>
                        <div class="large-3 column"><input type="text" name="wildcard" placeholder="Wildcard (comma separated)"></div>
                        <div class="large-3 column"><input type="submit" class="button primary" value="Add"></div>
                    </form>
               </div>
           </div>
       </div> 
    </div>
</body>
</html>