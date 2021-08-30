<?PHP
include_once("conn.class.php");
include_once("funcionario.class.php");
include_once("form.class.php");
session_start();

if (isset($_POST["login"])) {
    session_reset();
    $conn=new ConnBD();
    $dadosLogin=json_encode($_POST);
    $dadosLogin=json_decode($dadosLogin);
    $form = new Form("",$dadosLogin->email);
     $senha=$dadosLogin->senha;
    $senha=sha1($senha);
    $sql="SELECT * FROM `funcionario` WHERE email='$dadosLogin->email' AND senha='$senha' AND isAvaliador=1;";
    $conn->set_query($sql); 
    $conn->set_result();
    $resultado=$conn->get_result();
    if(mysqli_num_rows($resultado)>0){
        $rs=mysqli_fetch_assoc($resultado);
        $_SESSION["logado"]=true;
        $_SESSION["nome"]=$rs["nome"];
        $_SESSION["tipolog"]="avaliador";
        $_SESSION["email"]=$dadosLogin->email;
        echo "logado";
    }else {
        echo "erro";
    }
    $conn->close();
    exit(0);
}else if(isset($_POST["alterarSenha"])){
    $conn = new ConnBD();
    $dadosAlterarSenha=json_encode($_POST);
    $dadosAlterarSenha=json_decode($dadosAlterarSenha);
    if($_SESSION["logado"]!=true){
        echo "nao logado";
    }else{
         $senha=sha1($dadosAlterarSenha->senha);
        $sql="UPDATE `funcionario` SET senha = '$senha' WHERE email='".$_SESSION["email"]."'";
        $conn->set_query($sql);
        $conn->set_result();
        $resultado=$conn->get_result();
        if($resultado){
            echo "ok";
        }else{
            echo "erro";
        }
        $conn->close();
        exit(0);
    }
}else if(isset($_POST["processo"])){
    $conn = new ConnBD();
    $dados=json_encode($_POST);
    $dados=json_decode($dados);
    if($_SESSION["logado"]!=true){
        echo "nao logado";
    }else{
        $avaliador=$_SESSION["email"];
        $avaliado = new Funcionario($dados->nome,$dados->email,$dados->setor,$dados->cargo,$dados->empresa,$dados->isAvaliador,$dados->isGestor);
        if($avaliado->ChecarCadastro($conn)==-1){
            echo "Erro de conexão";
            $conn->close();
            exit(0);
        }else if($avaliado->ChecarCadastro($conn)==0){
            $resp =$avaliado->CadastraFuncionario($conn);
            if($resp==1){
            $query="INSERT INTO avaliado_avaliador(avaliado,avaliador) VALUES('$dados->email','$dados->cadavaliador');";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            echo "cadastrado";

        }
            
            
        }else{
            $avaliado->AtualizaCadastro($conn);
            $query="UPDATE  avaliado_avaliador SET avaliador = '$dados->cadavaliador' WHERE avaliado='$dados->email';";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            echo "atualizado";
        }
       /* $form = new Form($avaliado->email,$avaliador);
        //fluxo de cadastro completo
        //$form->abrirForm($conn);
        $rs=$form->getForm($conn,1);
        if($rs!==0){
            $num_rows=mysqli_num_rows($rs);
            echo $num_rows;
            while ($rs_fetch=mysqli_fetch_assoc($rs)) {
                echo "/";
                echo json_encode($rs_fetch);
            }
            $conn->close();
            exit();
        }*/
    }
}
else if(isset($_POST['avaliado'])){
    if($_SESSION["logado"]!=true){
        echo "nao logado";
    }else{
    $conn = new ConnBD();
    $avaliado=$_POST['avaliado'];
    $avaliador=$_SESSION["email"];
    $form = new Form($avaliado,$avaliador);
        //fluxo de cadastro completo
        $resultado= $form->abrirForm($conn);
        if($resultado==1){
            echo ("jaenviou");
            return 0;

        }
        $rs=$form->getForm($conn,1);
        if($rs!==0){
            $num_rows=mysqli_num_rows($rs);
            echo $num_rows;
            while ($rs_fetch=mysqli_fetch_assoc($rs)) {
                echo "/";
                echo json_encode($rs_fetch);
            }
            $conn->close();
            exit();
        }
        else{
            echo("erro");
        }
    }
}
else if(isset($_POST['acao']) && $_POST['acao']== "sair"){
    if($_SESSION["logado"]!=true){
        echo "Não logado";
    }else{
        session_destroy();
        echo "saiu";
    }
}
else if(isset($_POST['acao']) && $_POST['acao']== "exibirAutoavaliados"){
    $conn=new ConnBD();
    $form = new Form("",$_SESSION['email']);
    $respostas= $form->getNaoRespondidos($conn,3);
    echo(json_encode($respostas));
}



?>