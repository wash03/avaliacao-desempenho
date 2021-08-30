<?php 
include_once("funcionario.class.php");
include_once("conn.class.php");
include_once("form.class.php");
 
 session_start();
$conn= new ConnBD();

if($_POST['acao']=='preencheAvaliador'){
	if(!isset($_SESSION['tipolog'])){
 		echo "erro";
 	}
 	else if($_SESSION['tipolog']== "avaliador"){
		$dados[]= array("nome"=>$_SESSION["nome"],"email"=>$_SESSION["email"]);
		 echo(json_encode( $dados) );
	}
	else{
		$conn= new ConnBD();
    	$avaliador = new Funcionario($_SESSION["nome"],$_SESSION["email"],"","","","","");
    	$avaliador= $avaliador->getAvaliador($conn);
    	echo( json_encode( $avaliador) );
	}
 
 }
 if($_POST['acao']=='preencheEnvio'){
 	if(!isset($_SESSION['tipolog'])){
 		echo "erro";
 	}
 	else if($_SESSION['tipolog']=="avaliador"){
		$conn= new ConnBD();
    	$avaliados = new Funcionario("","","","","","","");
    	$avaliados = $avaliados->ListaAvaliado($conn,$_SESSION["email"]);
    	echo(json_encode($avaliados));
	}
	else{
		$dados[]= array("nome"=>$_SESSION["nome"],"email"=>$_SESSION["email"]);
		echo(json_encode($dados));
	}
 	
 }

 if($_POST['acao']=='preencheRelatorio'){
 	
		$conn= new ConnBD();
    	$relatorio = new Form("",$_SESSION["email"]);
    	$relatorios= $relatorio->getRespondidos($conn,1);
    	echo(json_encode($relatorios));
    	

	
	
 }

if($_POST['acao']=='preencheSetor'){

		$conn= new ConnBD();
    	$setor = new Funcionario("",$_SESSION["email"],"","","","","");
    	$setores= $setor->listaSetor($conn,$_POST['tipo']);
    	echo(json_encode($setores));
    	
	
	
 }

 if($_POST['acao']=='listaTodosConsesnsos'){

		
		$conn= new ConnBD();
    	$relatorio = new Form("",$_SESSION["email"]);
    	$relatorios= $relatorio->getRespondidos($conn,0);
    	echo(json_encode($relatorios));
    	
    	
	
	
 }

 
?>