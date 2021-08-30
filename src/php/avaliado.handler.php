
<?PHP
include_once("form.class.php");
include_once("conn.class.php");
include_once("funcionario.class.php");
session_start();

if (isset($_POST["login"])) {
    session_reset();
    $conn=new ConnBD();
    $dadosLogin=json_encode($_POST);
    $dadosLogin=json_decode($dadosLogin);
    //$senha=mysqli_real_escape_string($conn,$dadosLogin->senha );
    $senha=$dadosLogin->senha;
    $senha=sha1($senha);
    $sql="SELECT * FROM `funcionario` WHERE email='$dadosLogin->email' AND senha='$senha';";
    $conn->set_query($sql);
    $conn->set_result();
    $resultado=$conn->get_result();

    if(mysqli_num_rows($resultado)>0){
        $rs=mysqli_fetch_assoc($resultado);
        $_SESSION["logado"]=true;
        $_SESSION["nome"]=$rs["nome"];
        $_SESSION["tipolog"]="avaliado";
        $_SESSION["email"]=$dadosLogin->email;
      
        echo 1;//logado
    }
    else {
        echo -1;
        //não logado
    }
    $conn->close();
    exit(0);
}
if(isset($_POST["nome"])){
    //Envio do formulário
    $dados_form=json_encode($_POST);
    $dados_form=json_decode($dados_form);
    $conn= new ConnBD();

    $avaliado = new Funcionario($dados_form->nome,$dados_form->email,"","","","","");
    $avaliador = new Funcionario("",$dados_form->email_aux,"","","","","");
    
    switch ($avaliado->ChecarCadastro($conn)){
        case 1:
            $avaliado->getCadastro($conn);
            break;
        
        case -1:
            echo "Erro de conexão";
            $conn->close();
            exit(0);
            break;
    }
    switch ($avaliador->ChecarCadastro(($conn))) {
        case 0:
            echo "AvaliadorInvalido";
            $conn->close();
            exit(0);
            break;
            
        case 1:
            $avaliador->getCadastro($conn);
            break;
        
        case -1:
            echo "Erro de conexão";
            $conn->close();
            exit(0);
            break;
    }
    
    if($avaliador->isAvaliador!=1){
        echo "AvaliadorInvalido";
        $conn->close();
        exit(0);
    }
    $form = new Form($avaliado->email,$avaliador->email);
    $rs=$form->getForm($conn,2);
    if($rs!==0){
        $num_rows=mysqli_num_rows($rs);
        echo $num_rows;
        while ($rs_fetch=mysqli_fetch_assoc($rs)) {
            echo "/";
            echo json_encode($rs_fetch);
        }
        $conn->close();
        exit();
    }else{
        //Avaliado sem formulário para responder
        echo "semform";
        $conn->close();
        exit();
    }
}


 if(isset($_POST['acao']) && $_POST['acao']=="contagem"){

    $conn= new ConnBD();
    if($_SESSION['tipolog']=='avaliado'){
        $formulario = new Form($_SESSION['email'],'');      
        $num= $formulario->contaForm($conn,3);
        echo(json_encode($num));

    }
    else{
        $formulario = new Form('',$_SESSION['email']);      
        $num= $formulario->contaForm($conn,2);
        echo(json_encode($num));

    }
     
}
if(isset($_POST['acao']) && $_POST['acao']=="tipo"){
    
    if( isset($_SESSION["tipolog"]) && $_SESSION["tipolog"]== "avaliador"){
        echo 2; // está logado como avaliador
    }

    else if( isset($_SESSION["tipolog"]) && $_SESSION["tipolog"]== "avaliado" ){
        echo 1; // está logado como avaliado    
    }    
    
}

 if(isset($_POST["senha"])){
    $conn = new ConnBD();
    $dadosAlterarSenha=json_encode($_POST);
    $dadosAlterarSenha=json_decode($dadosAlterarSenha);
    if($_SESSION["logado"]!=true){
        echo 2;
    }else{
         $senha=sha1($dadosAlterarSenha->senha);
        $sql="UPDATE `funcionario` SET senha = '$senha' WHERE email='".$_SESSION["email"]."'";
        $conn->set_query($sql);
        $conn->set_result();
        $resultado=$conn->get_result();
        if($resultado){
            echo 1;
        }else{
            echo 0;
        }
        $conn->close();
        exit(0);
    }
}   
    

?>