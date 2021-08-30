<?php 
include_once("form.class.php");
include_once("conn.class.php");
 

 $conn = new ConnBD();


if(isset($_POST['tipo'])){
	session_start();
	
	if( $_POST['tipo']==3  && isset($_SESSION["tipolog"]) && $_SESSION["tipolog"]== "avaliador"){
		echo 4; // está logado como avaliador não pode responder como avaliado.
	}

	else if($_POST['tipo']!=3  && isset($_SESSION["tipolog"]) && $_SESSION["tipolog"]== "avaliado" ){
		echo 5; // está logado como avaliado não pode responder consenso e avaliado.
	}
		
	else if(!isset($_SESSION["logado"])){
		echo 6;// não está logado
	}		
	
else{
	$form = new Form($_POST['avaliado'],$_POST['avaliador']);
	$conn= new ConnBD();
	$resp=$form->carregaForm($conn,$_POST['tipo']);
    echo($resp);
}
	
    
}


else if(isset($_POST['acao'])){
	session_start();
	
	if( isset($_SESSION["tipolog"]) && $_SESSION["tipolog"]== "avaliador"){
		echo 2; // está logado como avaliador
	}

	else if( isset($_SESSION["tipolog"]) && $_SESSION["tipolog"]== "avaliado" ){
		echo 1; // está logado como avaliado	
	}
		
	
	
else{
	
    echo 0;
}
	
    
}






	 
	
?>




