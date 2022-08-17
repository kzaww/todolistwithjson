<?php
    $errors = '';


    $todo = $_POST['todo_name'] ?? '';
    $todo = trim($todo);
    
    if($todo){
        if(file_exists('todo.json')){
            $json = file_get_contents('todo.json');
            $jsonDe = json_decode($json,true);
        }else{
            $jsonDe =[];
        }

        $jsonDe[$todo] = ['done' => false];
        
        file_put_contents('todo.json',json_encode($jsonDe,JSON_PRETTY_PRINT));
    }

    if(isset($_POST['send'])){
        if(!$todo){
            $errors = 'You must Fill!!';
        }
    }


    $todos = [];
    if(file_exists('todo.json')){
        $json = file_get_contents('todo.json');
        $todos = json_decode($json,true);
    }



    if(isset($_POST['del_name'])){
        $del = $_POST['del_name'];
        $json = file_get_contents('todo.json');
        $jsonDe = json_decode($json,true);
        unset($jsonDe[$del]);
        file_put_contents('todo.json',json_encode($jsonDe,JSON_PRETTY_PRINT));

        header('Location: index.php');
    }

    if(isset($_POST['done_name'])){
        $done = $_POST['done_name'];
        $json = file_get_contents('todo.json');
        $jsonDe = json_decode($json,true);
        $jsonDe[$done]['done'] = !$jsonDe[$done]['done'];
        file_put_contents('todo.json',json_encode($jsonDe,JSON_PRETTY_PRINT));

        header('Location: index.php');
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO List</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="row">
        <div class="col-6 offset-3">
            <div class="card mt-5">
                <div class="card-header text-center bg-warning">
                    <h1>To DO List For Today</h1>
                </div>
                <div class="card-body">
                    <form action="index.php" method="POST">
                        <?php if(isset($errors)){ ?>
                            <span class="text-danger"><?php echo $errors ?></span>
                        <?php }  ?>
                        <div class="input-group">
                            <input type="text" class="form-control" name="todo_name" id="" autofocus>
                            <button type="submit" name="send" class="btn btn-secondary">SEND</button>
                        </div>
                    </form>
                </div>
            </div>

            <ul class='list-group text-center mt-4'>
                <?php foreach($todos as $name => $value){ ?>
                    <li class="list-group-items py-2 m-1 <?php echo $value['done'] ? 'bg-success' : 'bg-secondary' ?>" style="list-style:none;border-radius:4px;">
                        <form action="index.php" method="POST" class="d-inline">
                            <input type="hidden" name="done_name" value="<?php echo $name  ?>">
                            <input type="checkbox" <?php echo $value['done'] ? 'checked':''; ?> onchange="this.parentNode.submit()" class="float-start mt-2 ms-2" name="" id="">
                        </form>
                        <span class="text-white" style="font-size:20px;"><?php echo $name ?></span>
                        <form action="index.php" method="POST" style="display:inline;">
                            <input type="hidden" name="del_name" value="<?php echo $name  ?>">
                            <button  class="bg-danger float-end me-2 btn btn-sm" style="z-index : 99;">X</button>
                        </form>
                    </li>
                <?php }  ?>
            </ul>
        </div>
    </div>
</body>
<script src="../bootstrap/js/bootstrap.min.js"></script>
</html>