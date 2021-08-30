<?php 
include_once("form.class.php");
include_once("conn.class.php");

session_start();
//echo ( $_SESSION["email"]);

//if($_POST['acao']=='preenche'){
// $conn= new ConnBD();
 //   $avaliados = new Funcionario("","","","","","","");
 //   $avaliados= $avaliados->ListaFuncionario($conn);
 //   echo( json_encode( $avaliados) );
 //}
 $conn= new ConnBD();
        $relatorio = new Form("03874985326",'51568250363');
        $setor='RECEPÇÃO';
        $relatorios= $relatorio->conceitosSetores($conn);
       // var_dump($relatorios);
echo(json_encode($relatorios));

/*$conn= new ConnBD();
    	$setor = new Funcionario("","04303061301","","","","","");
    	$setores= $setor->listaSetor($conn,3);
    	var_dump($setores);
    	echo(json_encode($setores));*/
    	//SELECT * FROM formulario_consenso AS FC INNER JOIN respostas_consenso AS RC INNER JOIN funcionario AS F WHERE FC.email_avaliado= F.email AND F.setor= 'RECEPÇÂO' AND F.isAvaliador = 0 AND FC.isRespondido=1 AND RC.fk_idformulario=FC.id OR F.email= '04303061301' AND FC.media IS NOT NULL AND F.email = FC.email_avaliado and RC.fk_idformulario=FC.id order by area
?>

