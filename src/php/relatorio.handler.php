<?php
    include_once("conn.class.php");
    include_once("form.class.php");
    include_once("funcionario.class.php");
    session_start();
    $setor = new Funcionario("","04303061301","","","","","");
     header('Content-type: application/json');
    if(isset($_POST["individual"])){
        $conn = new ConnBD();
        $avaliador = json_decode(json_encode($_SESSION));
        if (isset($_POST["avaliado"])) {
            $avaliado = $_POST["avaliado"];
            $sql = "SELECT avaliado.isGerencial, avaliado.email AS email_avaliado, avaliado.nome AS nome_avaliado, avaliado.setor AS setor_avaliado, avaliado.cargo AS cargo_avaliado, respostas_consenso.data_fechamento AS data_consenso, respostas_consenso.respostas AS respostas_consenso
            FROM (funcionario AS avaliador INNER JOIN (funcionario AS avaliado INNER JOIN avaliado_avaliador ON avaliado.email = avaliado_avaliador.avaliado) ON avaliador.email = avaliado_avaliador.avaliador) INNER JOIN respostas_consenso ON (avaliado_avaliador.avaliado = respostas_consenso.email_avaliado) AND (avaliado_avaliador.avaliador = respostas_consenso.email_avaliador)
            WHERE (((respostas_consenso.isRespondido)=1) AND ((avaliado_avaliador.avaliador)='$avaliador->email') AND ((avaliado_avaliador.avaliado)='$avaliado'));";
            
        } else {
            $sql = "SELECT avaliado.isGerencial, avaliado.email AS email_avaliado, avaliado.nome AS nome_avaliado, avaliado.setor AS setor_avaliado, avaliado.cargo AS cargo_avaliado, respostas_consenso.data_fechamento AS data_consenso, respostas_consenso.respostas AS respostas_consenso
            FROM (funcionario AS avaliador INNER JOIN (funcionario AS avaliado INNER JOIN avaliado_avaliador ON avaliado.email = avaliado_avaliador.avaliado) ON avaliador.email = avaliado_avaliador.avaliador) INNER JOIN respostas_consenso ON (avaliado_avaliador.avaliado = respostas_consenso.email_avaliado) AND (avaliado_avaliador.avaliador = respostas_consenso.email_avaliador)
            WHERE (((respostas_consenso.isRespondido)=1) AND ((avaliado_avaliador.avaliador)='$avaliador->email'));";       
        }
        $conn->set_query($sql);
        $conn->set_result();
        $result=$conn->get_result();
        if(mysqli_num_rows($result)>0){
            echo $avaliador->nome;
            while($rs = mysqli_fetch_assoc($result)){
                echo "/";
                echo json_encode($rs);
            }
        }else{
            echo "semdados";
        }
        $conn->close();
        exit(0);
        /**
         * Retorna campos:
         * nome_avaliador
         * nome_avaliado
         * setor_avaliado
         * cargo_avaliado
         * data_consenso
         * respostas_consenso
         * isGerencial
         */
    }
    if(isset($_POST["acessoRelat"])){
        
        if(isset($_SESSION["email"])){
            $funcionario=$_SESSION["email"];
            $sql="SELECT * from funcionario WHERE email='$funcionario' ";
            $conn = new ConnBD();
            $conn->set_query($sql);
            $conn->set_result();
            $result=$conn->get_result();
            $row = $result->fetch_assoc() ;
            if($row['isGerencial']==1 ){
                echo 2;
            }
            else if($row['isAvaliador']==1){
                echo 3;
            }
            else{
                echo 4;
            }
       
        }
        else{
            echo 1;
        }
      
    }

    if(isset($_POST["RelatSetor"])){
        
        if(isset($_SESSION["email"])){
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->relatorioArea($conn,$_POST["setor"]);
        echo(json_encode($relatorios));

       
        }
        else{
            echo 1;
        }
      
    }

     if(isset($_POST["MedSetor"])){
        
        if(isset($_SESSION["email"])){
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->mediaGeral($conn,$_POST["setor"]);
        echo(json_encode($relatorios));

       
        }
        else{
            echo 1;
        }
      
    }
      if(isset($_POST["MedNaoGerente"])){
        
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->mediaGeral($conn,-1);
        echo(json_encode($relatorios));   
      
    }

    if(isset($_POST["MedGerente"])){
        
     
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->mediaGeral($conn,8);
        echo(json_encode($relatorios));

 
      
    }
//   medias dos funcionarios de um setor
     if(isset($_POST["MedFunc"])){
        
        if(isset($_SESSION["email"])){
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->mediafuncionarios($conn,$_POST["setor"]);
        echo(json_encode($relatorios));

       
        }
        else{
            echo 1;
        }
      
    }


      if(isset($_POST["Conceitofunc"])){
        
        if(isset($_SESSION["email"])){
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->porcentagemConceito($conn,0);
        echo(json_encode($relatorios));

       
        }
        else{
            echo 1;
        }
      
    }

       if(isset($_POST["ConceitoGerente"])){
        
        if(isset($_SESSION["email"])){
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->porcentagemConceito($conn,1);
        echo(json_encode($relatorios));

       
        }
        else{
            echo 1;
        }
      
    }


//consensos dos requisitos

       if(isset($_POST["ReqSet"])){
        
        if(isset($_SESSION["email"])){
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->requisitoSetor($conn,$_POST["requisito"]);
        echo(json_encode($relatorios));

       
        }
        else{
            echo 1;
        }
      
    }


//consensos dos setores
        if(isset($_POST["ConSet"])){
        
        if(isset($_SESSION["email"])){
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->conceitosSetores($conn);
        echo(json_encode($relatorios));

       
        }
        else{
            echo 1;
        }
      
    }

//consensos pendentes
         if(isset($_POST["ConPen"])){
        
        if(isset($_SESSION["email"])){
        $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->getNaoRespondidos($conn,1);
        echo(json_encode($relatorios));

       
        }
        else{
            echo 1;
        }
      
    }

//recupera avaliador e coloca no formulario

    if(isset($_POST["avaliado"])){
        
       $relat = new Funcionario("",$_POST["avaliado"],"","","","","");
         
        $conn = new ConnBD();
        $relat= $relat->getAvaliador($conn);
        echo(json_encode($relat));   
        
      
    }

//  relatorio de requisitos de não gerencia
        if(isset($_POST["RelatReqNãoG"])){
        
     $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->relatorioArea($conn,-1);
        echo(json_encode($relatorios));
        
      
    }

//  relatorio de requisitos de  gerencia
   if(isset($_POST["RelatReqGerente"])){
        
     $relatorio = new Form("",'');
        $conn = new ConnBD();
        $relatorios= $relatorio->relatorioArea($conn,8);
        echo(json_encode($relatorios));
        
      
    }


    //  porcentagem   não gerentes respondidos
   if(isset($_POST["FormsResp"])){
        
     $relatorio = new Form("",'');
        $conn = new ConnBD();
         $relatorios= $relatorio->qntdRespondidos($conn,2);
        echo(json_encode($relatorios));

        
      
    }


     if(isset($_POST["FormsRespGerente"])){
        
     $relatorio = new Form("",'');
        $conn = new ConnBD();
         $relatorios= $relatorio->qntdRespondidos($conn,1);
        echo(json_encode($relatorios));

        
      
    }


?>