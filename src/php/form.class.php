<?PHP
   require_once "funcionario.class.php";
    class Form
    {
        var $avaliado;
        var $avaliador;
        public function __construct($avaliado,$avaliador){
            $this->avaliado=$avaliado;
            $this->avaliador=$avaliador;
        }
        //METODOS
        function getForm(ConnBD $conn = null,$code = null){
            if(is_null($conn)||is_null($code)){
                return -1;
            }
            $avaliado=$this->avaliado;
            $avaliador=$this->avaliador;
            switch ($code) {
                //AVALIADOR
                case 1:
                    $query="SELECT * from formulario_consenso WHERE email_avaliado=$avaliado AND email_avaliador=$avaliador;";
                    break;
                //AVALIADO
                case 2:
                    $query="SELECT respostas_consenso.data_abertura, avaliado_avaliador.avaliado AS email_avaliado, funcionario.nome AS nome_avaliado, avaliado_avaliador.avaliador AS email_avaliador, funcionario_1.nome AS nome_avaliador, funcionario.isGerencial, respostas_consenso.data_fechamento AS data_consenso, respostas_avaliado.respostas AS resposta_avaliado, respostas_avaliador.respostas AS resposta_avaliador, respostas_consenso.respostas AS resposta_consenso, respostas_avaliado.isRespondido AS avaliado, respostas_avaliador.isRespondido AS avaliador, respostas_consenso.isRespondido AS consenso
                    FROM (((funcionario AS funcionario_1 INNER JOIN (funcionario INNER JOIN avaliado_avaliador ON funcionario.email = avaliado_avaliador.avaliado) ON funcionario_1.email = avaliado_avaliador.avaliador) INNER JOIN respostas_consenso ON (avaliado_avaliador.avaliado = respostas_consenso.email_avaliado) AND (avaliado_avaliador.avaliador = respostas_consenso.email_avaliador)) INNER JOIN respostas_avaliado ON (avaliado_avaliador.avaliado = respostas_avaliado.email_avaliado) AND (avaliado_avaliador.avaliador = respostas_avaliado.email_avaliador)) INNER JOIN respostas_avaliador ON (avaliado_avaliador.avaliado = respostas_avaliador.email_avaliado) AND (avaliado_avaliador.avaliador = respostas_avaliador.email_avaliador)
                    WHERE (((avaliado_avaliador.avaliado)='$this->avaliado') AND (((respostas_avaliado.isRespondido)=0) OR ((respostas_avaliador.isRespondido)=0) OR ((respostas_consenso.isRespondido)=0)));";
                    break;
                //AVALIADO E AVALIADOR
                case 3:
                $query="SELECT respostas_consenso.data_abertura, avaliado_avaliador.avaliado AS email_avaliado, funcionario.nome AS nome_avaliado, avaliado_avaliador.avaliador AS email_avaliador, funcionario_1.nome AS nome_avaliador, funcionario.isGerencial, respostas_consenso.data_fechamento AS data_consenso, respostas_avaliado.respostas AS resposta_avaliado, respostas_avaliador.respostas AS resposta_avaliador, respostas_consenso.respostas AS resposta_consenso, respostas_avaliado.isRespondido AS avaliado, respostas_avaliador.isRespondido AS avaliador, respostas_consenso.isRespondido AS consenso
                FROM (((funcionario AS funcionario_1 INNER JOIN (funcionario INNER JOIN avaliado_avaliador ON funcionario.email = avaliado_avaliador.avaliado) ON funcionario_1.email = avaliado_avaliador.avaliador) INNER JOIN respostas_consenso ON (avaliado_avaliador.avaliado = respostas_consenso.email_avaliado) AND (avaliado_avaliador.avaliador = respostas_consenso.email_avaliador)) INNER JOIN respostas_avaliado ON (avaliado_avaliador.avaliado = respostas_avaliado.email_avaliado) AND (avaliado_avaliador.avaliador = respostas_avaliado.email_avaliador)) INNER JOIN respostas_avaliador ON (avaliado_avaliador.avaliado = respostas_avaliador.email_avaliado) AND (avali
                ado_avaliador.avaliador = respostas_avaliador.email_avaliador)
                WHERE ((((avaliado_avaliador.avaliado)='$this->avaliado')AND ((avaliado_avaliador.avaliador)='$this->avaliador')) AND (((respostas_avaliado.isRespondido)=0) OR ((respostas_avaliador.isRespondido)=0) OR ((respostas_consenso.isRespondido)=0)));";
                    break;
            }
            $query="SELECT * from formulario_consenso WHERE email_avaliado=$avaliado AND email_avaliador=$avaliador;";
            $query="SELECT * FROM formulario_consenso WHERE email_avaliado='$avaliado' AND email_avaliador='$avaliador';";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            $rs= mysqli_fetch_array($rs);
            $conn->close();
            return $rs;
            
            
        }
        
        // abre os formulários
        function abrirForm(ConnBD $conn = null){
            if(is_null($conn)){
                return -1; // erro na conexão
            }
            $query="SELECT * from formulario_consenso WHERE email_avaliado='$this->avaliado' AND email_avaliador='$this->avaliador'";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            if(mysqli_num_rows($rs)>0){
                return 1; // formulário já aberto anteriormente

            }

            else{

            $now = new DateTime();
            $data = $now->format('Y-m-d');

            $query="INSERT INTO formulario_avaliado(data_abertura,email_avaliado,email_avaliador)".
            " VALUES('$data','$this->avaliado','$this->avaliador')";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            
            $query="INSERT INTO formulario_avaliador(data_abertura,email_avaliado,email_avaliador)".
            " VALUES('$data','$this->avaliado','$this->avaliador')";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            
            $query="INSERT INTO formulario_consenso(data_abertura,email_avaliado,email_avaliador)".
            " VALUES('$data','$this->avaliado','$this->avaliador')";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            $conn->close();
            return 0; // formulário aberto
            }
            
        }
        // verifica se há formulários disponíveis
         function carregaform(ConnBD $conn = null,$tipo){
            if(is_null($conn)){
                return -1;
            }
            $avaliado= $this->avaliado;
            $avaliador= $this->avaliador;
            if($tipo==3){
                $query="SELECT * FROM formulario_avaliado WHERE email_avaliado='$avaliado' AND email_avaliador='$avaliador' AND isRespondido=0";
                $av= 6;
               // formulário do avaliado

            }else if($tipo==2){
                $query="SELECT * FROM formulario_avaliador WHERE email_avaliado='$avaliado' AND email_avaliador='$avaliador' AND isRespondido=0";
                $av= 7;
                //formulário do avaliador
               
            }else if($tipo==1){
                  $query="SELECT * FROM formulario_consenso WHERE email_avaliado='$avaliado' AND email_avaliador='$avaliador' AND isRespondido=0";
                   $av= 8;
                   // formulário do consenso
                 
            }else{
                  return 0;
            }

            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
               
            if(mysqli_num_rows($rs)>0){

                $funcionario= new Funcionario('',$avaliado,'','','','','');
                $isAvaliador= $funcionario->verificaAvaliador($conn);
                if($isAvaliador==1){
                    return $av;

                }
                else{
                    return $tipo;  
                }
                    
            }else{
                 return 0; // sem formulário
            }         

    }   
    // verifica se há consenso respondido
         function recuperaForm(ConnBD $conn = null,$tipo){
            if(is_null($conn)){
                return -1;
            }
            $avaliado= $this->avaliado;
            $avaliador= $this->avaliador;
            if($tipo==3){
                $query="SELECT * FROM formulario_avaliado WHERE email_avaliado='$avaliado' AND email_avaliador='$avaliador' AND isRespondido=1";
                $av= 4;
               // formulário do avaliado

            }else if($tipo==2){
                $query="SELECT * FROM formulario_avaliador WHERE email_avaliado='$avaliado' AND email_avaliador='$avaliador' AND isRespondido=1";
                $av= 5;
                //formulário do avaliador
               
            }else if($tipo==1){
                  $query="SELECT * FROM formulario_consenso WHERE email_avaliado='$avaliado' AND email_avaliador='$avaliador' AND isRespondido=1";
                   $av= 6;
                   // formulário do consenso
                 
            }else{
                  return 0;
            }

            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
               
            if(mysqli_num_rows($rs)>0){

                $funcionario= new Funcionario('',$avaliado,'','','','','');
                $isAvaliador= $funcionario->verificaAvaliador($conn);
                if($isAvaliador==1){
                    return $av; // é um líder

                }
                else{
                    return $tipo; // não é um líder
                }
                    
            }else{
                 return 0; // sem formulário
            }         

    }   
    // conta o numero de formulários para serem respondidos
    function contaForm(ConnBD $conn = null,$tipo){
        if(is_null($conn)){
                return -1;
        }
        if($tipo==3){
            $avaliado=$this->avaliado;
             $query="SELECT * FROM formulario_avaliado WHERE email_avaliado='$avaliado' AND isRespondido=0";
             $query2="SELECT * FROM formulario_consenso WHERE email_avaliado='$avaliado' AND isRespondido=0";
        }

         else if($tipo==2){
            $avaliador=$this->avaliador;
            $query="SELECT * FROM formulario_avaliador WHERE email_avaliador='$avaliador' AND isRespondido=0";
            $query2="SELECT * FROM formulario_consenso WHERE  email_avaliador='$avaliador' AND isRespondido=0";
                   
        }
        else{
            return 0;
        }

        $conn->set_query($query);
        $conn->set_result();
        $rs=$conn->get_result();
        $numAv=mysqli_num_rows($rs);          
        $conn->set_query($query2);
        $conn->set_result();
        $rs=$conn->get_result();
        $conn->close();
        $numConsenso=mysqli_num_rows($rs);
        $num[] = array ("av"=> $numAv, "consenso"=> $numConsenso);
        return $num;
    }

    // retorna as respostas
     function getRespostas(ConnBD $conn = null,$tipo){

        if(is_null($conn)){
                return -1;
            }

            $avaliado= $this->avaliado;
            $avaliador= $this->avaliador;
            if($tipo==3){
                $sql="SELECT id FROM `formulario_avaliado` WHERE email_avaliado = '$avaliado' AND email_avaliador='$avaliador' AND isRespondido=1;";
              
            }else if($tipo==2){
                 $sql="SELECT id FROM`formulario_avaliador` WHERE email_avaliado = '$avaliado' AND email_avaliador='$avaliador' AND isRespondido=1;";
               
               
            }else if($tipo==1){
                 $sql="SELECT id FROM`formulario_consenso` WHERE email_avaliado = '$avaliado' AND email_avaliador='$avaliador' AND isRespondido=1;";
                
                 
            }else{
                  return 1;
            }  

            $conn->set_query($sql); 
            $conn->set_result();
            $resultado=$conn->get_result();           

            if(mysqli_num_rows($resultado)>0){                
                $rs=mysqli_fetch_assoc($resultado);
                $id= $rs["id"];
                if($tipo==3){
               
                    $sql2= "SELECT * FROM `respostas_avaliado` WHERE fk_idformulario = '$id';";
              
                }else if($tipo==2){
                
                    $sql2= "SELECT * FROM`respostas_avaliador` WHERE fk_idformulario = '$id';";
               
                }else if($tipo==1){
               
                    $sql2= "SELECT * FROM`respostas_consenso` WHERE fk_idformulario = '$id';";
                 
                }else{
                    return 1;
                 }  

                $conn->set_query($sql2); 
                $conn->set_result();
                $r=$conn->get_result();
                $conn->close();
                if(mysqli_num_rows($r)>0){
                while ( $row = $r->fetch_assoc() ) {
                        $respostas[] = array('area' => $row['area'],'pergunta'=>$row['pergunta'],'resposta'=> $row['resposta']);
                        
                    }
                 return $respostas;

                }
                else { return 2;}
            } 
            else { return 0;}

    }   
                    

        // retorna a letra mais escolhida em determinado formulário
        function estatisticaIndividual(ConnBD $conn = null,$tipo){
            $respostas= $this->getRespostas($conn,$tipo);
            $resp = array();
            
            for ($i = 0; $i < sizeof($respostas); $i++) {

                $resp[] = $respostas[$i]['resposta'];
                
            }

            $valores = array_count_values($resp);
            $valor = max( $valores );
            $letra = array_keys($valores, $valor);
            if($letra[0]=='A'){
                return 76;
            }

            else if($letra[0]=='B'){
                return 51;

            }
            else if ($letra[0]=='C'){
                return 26;

            }
            else{
                return 1;
          }
            
    } 
        // retorna a média de determinado formulário
        function media(ConnBD $conn = null,$tipo,$respostas){
           // $resp = array();
            $valores = array();            
            //for ($i = 0; $i < sizeof($respostas); $i++) {

                //$resp[] = $respostas[$i]['resposta'];
                
           // }
            $arrayCount = array_count_values($respostas);

            $valores[0] = 0;
            $valores[1] = 0;
            $valores[2] = 0;
            $valores[3] = 0;

            if(isset($arrayCount['A'])){
                $valores[0] = $arrayCount['A'];
            }
            if(isset($arrayCount['B'])){
                $valores[1] = $arrayCount['B'];
            }
            if(isset($arrayCount['C'])){
                $valores[2] = $arrayCount['C'];
            }
            if(isset($arrayCount['D'])){
                $valores[3] = $arrayCount['D'];
            }
                      
            $media = (76*$valores[0] + 51*$valores[1] + 26*$valores[2] + 1*$valores[3])/sizeof($respostas);
            if($media>75){
                $letra='A';
            }
            else if($media>50 && $media<76){
                $letra='B';
            }
            else if($media>25 && $media<51){
                $letra='C';
            }
            else {
                $letra='D';
            }
            $medias[] = array('valor' =>$media ,'letra'=>$letra );
            if($tipo==1){
                $avaliado= $this->avaliado;
                $avaliador= $this->avaliador;
                $sql="UPDATE `formulario_consenso`SET media='$letra',mediaNum=$media WHERE email_avaliado = 'avaliado' AND email_avaliador='$avaliador';";
                $conn->set_query($sql); 
                $conn->set_result();
                $r=$conn->get_result();
                $conn->close();

            }


            return $medias;
            
    } 
    // retorna a media do setor
    function mediaGeral(ConnBD $conn = null,$setor){
           
            $valores = array();            
            $medias = array();  
            $mediaGeral = array();  

            if($setor==0){// media geral
                $sql="SELECT media FROM `formulario_consenso` WHERE media IS NOT NULL";
                $conn->set_query($sql); 
                $conn->set_result();
                $respostas=$conn->get_result();
                if(mysqli_num_rows($respostas)==0){
                    return "sem";
                }
                while ( $row = $respostas->fetch_assoc() ) {
                    $m=$row['media'];
                    $medias[] = $m;
                }
            }
             else if($setor==-1){ // media geral denão gerentes
                $sql="SELECT media FROM `formulario_consenso` as FC INNER JOIN funcionario AS F WHERE  FC.email_avaliado= F.email AND F.isAvaliador= 0  AND FC.media IS NOT NULL";
                $conn->set_query($sql); 
                $conn->set_result();
                $respostas=$conn->get_result();
                if(mysqli_num_rows($respostas)==0){
                    return "sem";
                }
                while ( $row = $respostas->fetch_assoc() ) {
                    $m=$row['media'];
                    $medias[] = $m;
                }
            }
            else{
                $av= 0;
                $sql="SELECT * FROM `setor` WHERE idsetor='$setor'";
                $conn->set_query($sql); 
                $conn->set_result();
                $respostas=$conn->get_result();
                while ($row = $respostas->fetch_assoc() ) {
                $set=$row['nome'];
                $resp=$row['responsavel'];
                if($set=='GESTÃO'){ // media geral de gerentes
                   $sql="SELECT media FROM `formulario_consenso` as FC INNER JOIN funcionario AS F WHERE  FC.email_avaliado= F.email AND F.isAvaliador= 1  AND F.isGerencial=0 AND FC.media IS NOT NULL";
                }
                  else if($set=="DIRETORIA"){
                         $sql="SELECT media FROM `formulario_consenso` as FC INNER JOIN funcionario AS F WHERE  FC.email_avaliado= F.email AND F.isAvaliador= 1  AND F.setor= '$set' AND FC.media IS NOT NULL";

                     }
                else{ // media geral de algum setor 
                     $sql="SELECT media FROM `formulario_consenso` as FC INNER JOIN funcionario AS F WHERE  FC.email_avaliado= F.email AND F.setor= '$set'  AND F.isAvaliador= 0 AND FC.media IS NOT NULL ; ";
                }
               
                $conn->set_query($sql); 
                $conn->set_result();
                $respostas=$conn->get_result();
                 if(mysqli_num_rows($respostas)==0){
                    return "sem";
                }
                 while ( $row = $respostas->fetch_assoc() ) {
                    $m=$row['media'];
                    $medias[] = $m;
                    }
                }

            }          

            $valores[0] = 0;
            $valores[1] = 0;
            $valores[2] = 0;
            $valores[3] = 0;
            $arrayCount = array_count_values($medias);

            if(isset($arrayCount['A'])){
                $valores[0] = $arrayCount['A'];
            }
            if(isset($arrayCount['B'])){
                $valores[1] = $arrayCount['B'];
            }
            if(isset($arrayCount['C'])){
                $valores[2] = $arrayCount['C'];
            }
            if(isset($arrayCount['D'])){
                $valores[3] = $arrayCount['D'];
            }
                      
            $media = (76*$valores[0] + 51*$valores[1] + 26*$valores[2] + 1*$valores[3])/sizeof($medias);
            if($media>75){
                $letra='A';
            }
            else if($media>50 && $media<76){
                $letra='B';
            }
            else if($media>25 && $media<51){
                $letra='C';
            }
            else {
                $letra='D';
            }
            $arrayName = array('media' => $media, 'letra' => $letra);
            return $arrayName;
            
        } 
// retorna formulários não respondidos ainda
  function getNaoRespondidos(ConnBD $conn = null,$tipo){
        $avaliador =$this->avaliador;
        $funcionarios= array();
        $nomes= array();

        if($tipo==3){
             $sql="SELECT email_avaliado FROM `formulario_avaliado` WHERE email_avaliador = '$avaliador' AND isRespondido=0;";
             $conn->set_query($sql);
             $conn->set_result();
             $rs=$conn->get_result();
             if(mysqli_num_rows($rs)==0){
                $nomes[] = array('nome' => 'Todos já responderam ou não foram enviados formulários.');

             } 
             else{
            while ( $row = $rs->fetch_assoc() ) {
                $email=$row['email_avaliado'];
                $sql="SELECT nome FROM `funcionario` WHERE email = '$email'";
                $conn->set_query($sql);
                $conn->set_result();
                $r=$conn->get_result();
                $r=$r->fetch_assoc();
                $nome=$r['nome'];
                $nomes[] = array('nome' => $nome);
            }

             $conn->close();

             }  
         }          
              

        else  if($tipo==2){
            $sql="SELECT email_avaliado FROM `formulario_avaliador` WHERE email_avaliador = '$avaliador' AND isRespondido=0;";

        }
        else  if($tipo==1){
             $sql="SELECT  email_avaliado,email_avaliador FROM `formulario_consenso` WHERE  isRespondido=0;";
             $conn->set_query($sql);
             $conn->set_result();
             $rs=$conn->get_result();
             if(mysqli_num_rows($rs)==0){
                $nomes[] = array('nome' => 'Todos já responderam ou não foram enviados formulários.');

             }

            else{
            while ($row = $rs->fetch_assoc() ) {
                $email=$row['email_avaliado'];
                $sql="SELECT nome,setor FROM `funcionario` WHERE email = '$email'";
                $conn->set_query($sql);
                $conn->set_result();
                $r=$conn->get_result();
                $r=$r->fetch_assoc();
                $nome=$r['nome'];
                $setor=$r['setor'];
                $nomes[] = array('nome' => $nome,'setor' => $setor);
            }

             $conn->close();

             }  

        }
        else{ return 0;}
        

        return $nomes;
   
        
    }
    // retorna formulários respondidos
    function getRespondidos(ConnBD $conn = null,$tipo){
         if(is_null($conn)){
                return -1; // erro na conexão
            }
        $avaliador =$this->avaliador;
        $funcionarios= array();
        $nomes= array();

          if($tipo==1){
             $sql="SELECT email_avaliado,nome,email FROM `formulario_consenso` AS FC INNER JOIN funcionario AS F  WHERE FC.email_avaliador = '$avaliador' AND FC.isRespondido=1 AND FC.email_avaliado=F.email ORDER BY F.nome;";
              }
        else if($tipo==0){
            $sql="SELECT email_avaliado,nome,email FROM `formulario_consenso`  AS FC INNER JOIN funcionario AS F WHERE  FC.isRespondido=1 AND FC.email_avaliado=F.email ORDER BY F.nome;";
        }
        else{ return 0;}
             $conn->set_query($sql);
             $conn->set_result();
             $rs=$conn->get_result();
             if(mysqli_num_rows($rs)==0){
                $nomes[] = array('nome' => 'Sem consensos respondidos');

             } 
             else{
                while ( $row = $rs->fetch_assoc() ) {
                
                $nome=$row['nome'];
                $email= $row['email'];
                $nomes[] = array('nome' => $nome, 'email' => $email);
            }

             $conn->close();

             }  
                

        return $nomes;  
        
    }
    // retorna a media dos requisitos do setor 
    function relatorioArea(ConnBD $conn = null, $setor){
         if(is_null($conn)){
                return -1; // erro na conexão
            }

        if($setor==0){
         $sql="SELECT * FROM `respostas_consenso` order by area;";
   
    
        }
        else if($setor==-1){
             $sql="SELECT area, resposta FROM formulario_consenso AS FC INNER JOIN respostas_consenso AS RC INNER JOIN funcionario AS F WHERE  FC.email_avaliado= F.email  AND F.isAvaliador = 0 AND FC.isRespondido=1  AND RC.fk_idformulario=FC.id  order by area;"; 

        }
        else{
            
            $sql="SELECT * FROM setor WHERE idsetor='$setor'; ";
            $conn->set_query($sql);
            $conn->set_result();
            $r=$conn->get_result();
            if(mysqli_num_rows($r)>0){
            $set=mysqli_fetch_assoc($r);
            $nome=$set['nome'];
            $resp=$set['responsavel'];
            $av= 0;
             if($nome=='GESTÃO'){
                $sql="SELECT area, resposta FROM formulario_consenso AS FC INNER JOIN respostas_consenso AS RC INNER JOIN funcionario AS F WHERE  FC.email_avaliado= F.email  AND F.isAvaliador = 1 AND FC.isRespondido=1  AND RC.fk_idformulario=FC.id AND F.isGerencial=0  order by area;"; 
            }
             else if($nome=='DIRETORIA'){
                $sql="SELECT area, resposta FROM formulario_consenso AS FC INNER JOIN respostas_consenso AS RC INNER JOIN funcionario AS F WHERE  FC.email_avaliado= F.email  AND F.isAvaliador = 1 AND FC.isRespondido=1  AND RC.fk_idformulario=FC.id AND F.setor= '$nome'  order by area;"; 
            }

            else{
            $sql="SELECT area, resposta FROM formulario_consenso AS FC INNER JOIN respostas_consenso AS RC INNER JOIN funcionario AS F WHERE  FC.email_avaliado= F.email AND F.setor= '$nome' AND F.isAvaliador = '$av' AND FC.isRespondido=1  AND RC.fk_idformulario=FC.id  order by area;"; 
            }
          
        }else {return 0;}
           

        }
         $conn->set_query($sql);
         $conn->set_result();
         $re=$conn->get_result();
         if(mysqli_num_rows($re)==0){
                    return "sem";
        }
         $mediaArea=0;
         $cont=0;
         $medias= array();
         $area='1.Conhecimento';


         while ($row = $re->fetch_assoc() ) {

                  
               if($area!= $row['area']){
                    $mediaArea=$mediaArea/$cont;
                    $medias[]=array('area' => $area, 'media'=>$mediaArea,'cor'=>'#b87333');
                    $cont=0;
                    $mediaArea=0;
                    $cor=$row['area'];
                    $area=$row['area'];
                }

                           
                    if($row['resposta']=='A'){
                        $mediaArea=$mediaArea+76;
                    }
                     else if($row['resposta']=='B'){
                        $mediaArea=$mediaArea+51;
                    }
                     else if($row['resposta']=='C'){
                        $mediaArea=$mediaArea+26;
                    }
                     else  if($row['resposta']=='D'){
                        $mediaArea=$mediaArea+1;
                    }
                    else{
                        return 0;
                    }
                    $cont=$cont+1;     
            
        }
        if($cont!=0){
        $mediaArea=$mediaArea/$cont;
        $medias[]=array('area' => $area, 'media'=>$mediaArea);
        return $medias;  
        }
        else{ return 1;}
           

    }

        // retorna a media dos funcionarios do setor
    function mediafuncionarios(ConnBD $conn = null,$setor){
         if(is_null($conn)){
                return -1; // erro na conexão
            }
           
           
            $relatorio = array();  

            if($setor==0){
                $sql="SELECT media,mediaNum FROM `formulario_consenso` WHERE media IS NOT NULL";
                $conn->set_query($sql); 
                $conn->set_result();
                $respostas=$conn->get_result();
                if(mysqli_num_rows($respostas)==0){
                    return "sem";
                }
                while ( $row = $respostas->fetch_assoc() ) {
                    $m=$row['media'];
                    $medias[] = $m;
                }
            }
            else{
                
                $sql="SELECT * FROM `setor` WHERE idsetor='$setor'";
                $conn->set_query($sql); 
                $conn->set_result();
                $respostas=$conn->get_result();
                $av = 0;
                while ($row = $respostas->fetch_assoc() ) {
                $set=$row['nome'];
                $resp=$row['responsavel'];
                if($set=='GESTÃO'){
                     $sql="SELECT media, nome,mediaNum FROM `formulario_consenso`  AS FC INNER JOIN funcionario AS F WHERE F.isAvaliador= 1  AND F.isGerencial=0 AND FC.media IS NOT NULL AND F.email = FC.email_avaliado  ORDER BY F.nome";
                }
                else if($set=="DIRETORIA"){
                         $sql="SELECT media, nome,mediaNum FROM `formulario_consenso`  AS FC INNER JOIN funcionario AS F WHERE F.setor= '$set' AND F.isAvaliador = 1 AND FC.media IS NOT NULL AND F.email = FC.email_avaliado ";

                }
                else{
                $sql="SELECT media, nome,mediaNum FROM `formulario_consenso`  AS FC INNER JOIN funcionario AS F WHERE F.setor= '$set' AND F.isAvaliador = '$av' AND FC.media IS NOT NULL AND F.email = FC.email_avaliado ";
                 }
                 $conn->set_query($sql); 
                $conn->set_result();
                $respostas=$conn->get_result();
                 if(mysqli_num_rows($respostas)==0){
                    return "sem";
                }
                 while ( $row = $respostas->fetch_assoc() ) {
                     $relatorio[]=array('nome' => $row['nome'], 'conceito'=>$row['media'], 'valor'=>$row['mediaNum']);
                    }
                }

            }          

            
          
             return $relatorio; 
            
        } 

        // porcentagem dos funcionarios para cada conceito
        function porcentagemConceito (ConnBD $conn = null,$gerente){
             if(is_null($conn)){
                return -1; // erro na conexão
            }
           
                $sql="SELECT media FROM `formulario_consenso` as FC INNER JOIN funcionario AS F WHERE F.isAvaliador='$gerente'  AND FC.email_avaliado= F.email AND F.isGerencial=0 AND FC.media IS NOT NULL";
                $conn->set_query($sql); 
                $conn->set_result();
                $dados=$conn->get_result();
                 $medias = array() ;
                 $porcentagem = array() ;
                 $total = mysqli_num_rows($dados);
                
                 while ( $row = $dados->fetch_assoc() ) {
                     $medias[]= $row['media'];
                    }
                 $quantidades  = array_count_values($medias);
                 if(!isset($quantidades['D'])){
                      $quantidades['D'] =0;
                 }
                  if(!isset($quantidades['C'])){
                      $quantidades['C'] =0;
                 }
                  if(!isset($quantidades['B'])){
                      $quantidades['B'] =0;
                 }
                  if(!isset($quantidades['A'])){
                      $quantidades['A'] =0;
                 }

                 if($total==0){
                    $total=1;
                 }

                 $porcentagem[]   = array('A' => ($quantidades['A']*100)/$total );
                 $porcentagem[]   = array('B' => ($quantidades['B']*100)/$total );
                 $porcentagem[]   = array('C' => ($quantidades['C']*100)/$total );
                 $porcentagem[]   = array('D' => ($quantidades['D']*100)/$total );
                    
                
                
                return $porcentagem;

        }


        // requisito por setor retornar:setor e media do requisito 
        function requisitoSetor (ConnBD $conn = null,$requisito){
             if(is_null($conn)){
                return -1; // erro na conexão
            }
           
               // $sql="SELECT resposta FROM `respostas_consenso` as RC INNER JOIN funcionario AS F INNER JOIN formulario_consenso AS FC WHERE RC.area='$requisito'  AND FC.email_avaliado= F.email AND FC.media IS NOT NULL AND F.setor ='RECEPÇÃO' AND FC.id=RC.fk_idformulario";
                $sql="SELECT * from setor ORDER BY nome";
                $conn->set_query($sql); 
                $conn->set_result();
                $dados=$conn->get_result();
                 $medias = array() ;
                 $total = mysqli_num_rows($dados);


                
                 while ( $row = $dados->fetch_assoc() ) {
                     $setor = $row['nome'];
                     $responsavel=$row['responsavel'];
                    // $relatorios = $relatorio->relatorioArea($conn,$setor);
                     if($setor=="GESTÃO"){
                         $sql="SELECT resposta FROM `respostas_consenso` as RC INNER JOIN funcionario AS F INNER JOIN formulario_consenso AS FC WHERE RC.area='$requisito'  AND FC.media IS NOT NULL AND FC.id=RC.fk_idformulario AND F.isGerencial=0 AND FC.email_avaliado= F.email AND  F.isAvaliador =1; ";

                     }
                     else if($setor=="DIRETORIA"){
                         $sql="SELECT resposta FROM `respostas_consenso` as RC INNER JOIN funcionario AS F INNER JOIN formulario_consenso AS FC WHERE RC.area='$requisito'  AND FC.media IS NOT NULL AND FC.id=RC.fk_idformulario  AND FC.email_avaliado= F.email AND F.setor ='$setor' AND  F.isAvaliador =1; ";

                     }
                     else{
                         $sql="SELECT resposta FROM `respostas_consenso` as RC INNER JOIN funcionario AS F INNER JOIN formulario_consenso AS FC WHERE RC.area='$requisito'  AND FC.media IS NOT NULL AND FC.id=RC.fk_idformulario AND FC.email_avaliado= F.email AND  F.setor ='$setor' AND  F.isAvaliador =0; ";
                     }

                    
                    $conn->set_query($sql); 
                    $conn->set_result();
                    $med=$conn->get_result();
                    $total = mysqli_num_rows($med);
                    $media=0;
                    if($total!=0){
                        //return "sem"; // requisito do setor nao foi respondido

                   
                    while ( $linha = $med->fetch_assoc() ) {
                        if($linha['resposta']=='A'){
                        $media=$media+76;
                    }
                     else if($linha['resposta']=='B'){
                        $media=$media+51;
                    }
                     else if($linha['resposta']=='C'){
                        $media=$media+26;
                    }
                     else  if($linha['resposta']=='D'){
                        $media=$media+1;
                    }
                    else{
                        return 0;
                    }

                     }

                     $media=$media/$total;

                 }
                    $medias[]=array ('setor'=> $setor, 'media'=> $media);

                    }


                    return $medias;
              

        }

          // conceitos dos setores
        function conceitosSetores(ConnBD $conn = null){
             if(is_null($conn)){
                return -1; // erro na conexão
            }
           
                $sql="SELECT * from setor ORDER BY nome";
                $conn->set_query($sql); 
                $conn->set_result();
                $dados=$conn->get_result();
                $medias = array() ;
                $total = mysqli_num_rows($dados);
                $media=0;

                
                 while ( $row = $dados->fetch_assoc() ) {     
                    
                    $me=$this->mediaGeral($conn,$row['idsetor']);
                    if($me!="sem"){
                        $media=$me['media'];
                    }
                    else{
                        $media=0;
                    }
                    
                  
                    $medias[]=array ('setor'=> $row['nome'], 'media'=> $media);
                   

                }


                    return $medias;
              

        }


        function qntdRespondidos(ConnBD $conn = null, $tipofuncionario){
             if(is_null($conn)){
                return -1; // erro na conexão
            }
           
           if($tipofuncionario==0){
             $sql="SELECT * from formulario_consenso WHERE media IS NOT NULL AND isRespondido=1;";
              $query="SELECT * from funcionario  ;";
              $total=-2;


           }
           else if($tipofuncionario==1){
             $sql="SELECT * FROM `formulario_consenso` as FC INNER JOIN funcionario AS F WHERE  FC.media IS NOT NULL AND FC.email_avaliado= F.email AND F.isAvaliador=1 ";
               $query="SELECT * from funcionario WHERE isAvaliador=1 ;";
                $total=-3;

           }
           else if ($tipofuncionario==2){
             $sql="SELECT * FROM `formulario_consenso` as FC INNER JOIN funcionario AS F WHERE  FC.media IS NOT NULL AND FC.email_avaliado= F.email AND F.isAvaliador=0 ";
              $query="SELECT * from funcionario WHERE isAvaliador=0 ;";
               $total=0;
              //$total=79;



           }
           else{
            return -1;
           }
               
                $conn->set_query($sql); 
                $conn->set_result();
                $dados=$conn->get_result();
                $respondidos = mysqli_num_rows($dados);
                $conn->set_query($query); 
                $conn->set_result();
                $dads=$conn->get_result();
                $total =$total+ mysqli_num_rows($dads);
                $conn->close();
                $porcentagem=($respondidos*100)/$total;
                $porcentagem=round($porcentagem, 2);
                $resp = array('porcentagem' => $porcentagem,'numero'=>$respondidos);
                return $resp;
                

        }


    
}
?>