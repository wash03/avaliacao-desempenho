<?php
include_once("form.class.php");
include_once("conn.class.php");
include_once("funcionario.class.php");
session_start();


if(isset($_GET["select"])){
    $json_form=json_encode($_GET);
    $json_form=json_decode($json_form);
    $conn= new ConnBD();
    $form = new Form($json_form->avaliado,$json_form->avaliador);
    $rs=$form->getForm($conn,3);
    $rs=mysqli_fetch_assoc($rs);
    if(isset($_SESSION["logado"])){
        $rs["isAvaliador"]=true;
    }else {
        $rs["isAvaliador"]=false;
    }
    echo json_encode($rs);
    $conn->close();
}else if($_POST["tipo"]){
    $dados=json_encode($_POST);
    $dados=json_decode($dados);
    $conn= new ConnBD();
    $respostas =$dados->dados;
    $respostas= json_decode($respostas);
    $form = new Form($dados->avaliado,$dados->avaliador);
    
   
    if ($dados->tipo==3){
        $sql="SELECT id FROM`formulario_avaliado` WHERE email_avaliado = '$dados->avaliado' AND email_avaliador='$dados->avaliador' AND isRespondido=0;";
        $conn->set_query($sql); 
        $conn->set_result();
        $resultado=$conn->get_result();
        if(mysqli_num_rows($resultado)>0){
         $rs=mysqli_fetch_assoc($resultado);
            $id= $rs["id"];
        }
        if(isset($id)){
            $sql="UPDATE `formulario_avaliado`SET data_fechamento='$dados->data',isRespondido=1 WHERE id = '$id';";
            $conn->set_query($sql); 
            $conn->set_result();
            for( $i =0; $i<$dados->tamanho;$i++){
                $respostas[$i] = (array) $respostas[$i];
                $resposta= $respostas[$i]['resposta'];
                $area= $respostas[$i]['label'];
                $pergunta= $respostas[$i]['name'];
                $sql="INSERT INTO `respostas_avaliado` (fk_idformulario,pergunta,resposta,area) values ('$id','$pergunta', '$resposta', '$area');";
                $conn->set_query($sql);
                $conn->set_result();
            }
             echo 1;

        } else{ echo 0;}
        
    }elseif ($dados->tipo==2) {
        $sql="SELECT id FROM`formulario_avaliador` WHERE email_avaliado = '$dados->avaliado' AND email_avaliador='$dados->avaliador' AND isRespondido=0;";
        $conn->set_query($sql); 
        $conn->set_result();
        $resultado=$conn->get_result();
        if(mysqli_num_rows($resultado)>0){
         $rs=mysqli_fetch_assoc($resultado);
            $id= $rs["id"];
        }
        if(isset($id)){
            $sql="UPDATE `formulario_avaliador`SET data_fechamento='$dados->data',isRespondido=1 WHERE id = '$id';";
            $conn->set_query($sql); 
            $conn->set_result();
            for( $i =0; $i<$dados->tamanho;$i++){
                $respostas[$i] = (array) $respostas[$i];
                $resposta= $respostas[$i]['resposta'];
                $area= $respostas[$i]['label'];
                $pergunta= $respostas[$i]['name'];
                $sql="INSERT INTO `respostas_avaliador` (fk_idformulario,pergunta,resposta,area) values ('$id','$pergunta', '$resposta', '$area');";
                $conn->set_query($sql);
                $conn->set_result();
            }
        echo 1;

        } else{ echo 0;}
        
    }elseif ($dados->tipo==1){
        $sql="SELECT id FROM`formulario_consenso` WHERE email_avaliado = '$dados->avaliado' AND email_avaliador='$dados->avaliador' AND isRespondido=0;";
        $conn->set_query($sql); 
        $conn->set_result();
        $resultado=$conn->get_result();
        if(mysqli_num_rows($resultado)>0){
         $rs=mysqli_fetch_assoc($resultado);
            $id= $rs["id"];
        }
        if(isset($id)){
            $sql="UPDATE `formulario_consenso`SET data_fechamento='$dados->data',isRespondido=1 WHERE id = '$id';";
            $conn->set_query($sql); 
            $conn->set_result();
            for( $i =0; $i<$dados->tamanho; $i++){
                $respostas[$i] = (array) $respostas[$i];
                $resposta= $respostas[$i]['resposta'];
                $area= $respostas[$i]['label'];
                $pergunta= $respostas[$i]['name'];
                $sql="INSERT INTO `respostas_consenso` (fk_idformulario,pergunta,resposta,area) values('$id','$pergunta', '$resposta', '$area');";
                $conn->set_query($sql);
                $conn->set_result();
            }

          echo 1;   

        } else{ echo 0;}
    }
    else{
        echo 0; // erro
    }

    $conn->close();
}

else if(isset($_POST["acao"]) && $_POST["acao"]=='media'){
    $json_form=json_encode($_GET);
    $json_form=json_decode($json_form);
    $conn= new ConnBD();
    $form = new Form($json_form->avaliado,$json_form->avaliador);
    $rs=$form->getForm($conn,3);
    $rs=mysqli_fetch_assoc($rs);
    if(isset($_SESSION["logado"])){
        $rs["isAvaliador"]=true;
    }else {
        $rs["isAvaliador"]=false;
    }
    echo json_encode($rs);
    $conn->close();
}




   
   


?>