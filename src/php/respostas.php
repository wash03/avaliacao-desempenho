<?php 
include_once("form.class.php");
include_once("conn.class.php");


if(isset($_POST['acao']) && $_POST['acao']=="preencheRespostaAvaliado"){

	$form = new Form($_POST['avaliado'],$_POST['avaliador']);
	$conn= new ConnBD();
	$resp=$form->getRespostas($conn,3);
	echo(json_encode($resp));  
}


if(isset($_POST['acao']) && $_POST['acao']=="preencheRespostaAvaliador"){

	$form = new Form($_POST['avaliado'],$_POST['avaliador']);
	$conn= new ConnBD();
	$resp=$form->getRespostas($conn,2);
	echo(json_encode($resp));	  
}

if(isset($_POST['acao']) && $_POST['acao']=="preencheRespostaConsenso"){
	$form = new Form($_POST['avaliado'],$_POST['avaliador']);
	$conn= new ConnBD();
	$resp=$form->getRespostas($conn,1);
	echo(json_encode($resp));	  
}


if(isset($_POST['acao']) && $_POST['acao']=="gerarMedia"){
	$dados=json_encode($_POST);
    $dados=json_decode($dados);
	$form = new Form($dados->avaliado,$dados->avaliador);
	$conn= new ConnBD();
	$resposta = array();
	$respostas =$dados->dados;
	$respostas= json_decode($respostas);
	$resposta= array();  
	for( $i =0; $i<$_POST['tamanho']-1;$i++){
		 $respostas[$i] = (array) $respostas[$i];
         $resposta[$i]= $respostas[$i]['resposta'];      
    } 
	$resp=$form->media($conn,1,$resposta);
	echo(json_encode($resp));
	  
}
 if(isset($_POST['acao']) && $_POST['acao']=="salvarMedia"){
    $dados=json_encode($_POST);
    $dados=json_decode($dados);
    $letra=$dados->conceito;
    $conn= new ConnBD();
    $sql="UPDATE `formulario_consenso`SET media='$letra' WHERE email_avaliado = '$dados->avaliado' AND email_avaliador='$dados->avaliador';";
    $conn->set_query($sql); 
   $conn->set_result();
   $r=$conn->get_result();
   $conn->close();  
    echo 2;
    
      
}

// verifica se o consenso foi respondido e se é um líder ou não

if(isset($_POST['acao']) && $_POST['acao']=="recuperarForm"){

	$form = new Form($_POST['avaliado'],$_POST['avaliador']);
	$conn= new ConnBD();
	$resp=$form->recuperaForm($conn,$_POST['tipo']);
    echo($resp);
		  
} 

if(isset($_POST['acao']) && $_POST['acao']=="recuperarDataConsenso"){

	$form = new Form($_POST['avaliado'],$_POST['avaliador']);
	$conn= new ConnBD();
	$resp=$form->getForm($conn,$_POST['tipo']);
    echo($resp['data_fechamento']);
		  
} 

 



?>