<?PHP
    class Funcionario
    {
        var $nome;
        var $email;
        var $setor;
        var $cargo;
        var $empresa;
        var $isAvaliador;
        var $isGerencial;
        public function __construct($nome,$email,$setor,$cargo,$empresa,$isAvaliador,$isGerencial){
            $this->nome=strtoupper($nome);
            $this->email=$email;
            $this->setor=strtoupper($setor);
            $this->cargo=strtoupper($cargo);
            $this->empresa=strtoupper($empresa);
            $this->isAvaliador=$isAvaliador;
            $this->isGerencial=$isGerencial;
        }

    //metodos
    function ChecarCadastro(ConnBD $conn = null)
    {
        $dados=$this;
        if ($dados==null||$conn==null) {
            return -1;
        } else {
            $query="SELECT * FROM `funcionario` WHERE funcionario.email='$dados->email'";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            if (mysqli_num_rows($rs)>0) {
                /*
                $rs=mysqli_fetch_assoc($rs);
                $this->isAvaliador=$rs["isAvaliador"];
                */
                return 1;
            }else{
                return 0;
            }    
        }
    }
    function getCadastro(ConnBD $conn = null)
    {
        $dados=$this;
        if ($dados==null||$conn==null) {
            return -1;
        } else {
            $query="SELECT * FROM `funcionario` WHERE funcionario.email='$dados->email'";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            if (mysqli_num_rows($rs)>0) {
                $rs=mysqli_fetch_assoc($rs);
                $this->nome=$rs["nome"];
                $this->email=$rs["email"];
                $this->setor=$rs["setor"];
                $this->cargo=$rs["cargo"];
                $this->empresa=$rs["empresa"];
                $this->isAvaliador=$rs["isAvaliador"];
                $this->isGerencial=$rs["isGerencial"];
                return 1;
            }else{
                return 0;
            }    
        }
    }
     function BuscaFuncionario(ConnBD $conn = null)
    {
        $dados=$this;
        if ($dados==null||$conn==null) {
            return -1;
        } else {
            $query="SELECT * FROM `funcionario` WHERE funcionario.email='$dados->email'";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            if (mysqli_num_rows($rs)>0) {
                $rs=mysqli_fetch_assoc($rs);
                $this->nome=$rs["nome"];
                $this->email=$rs["email"];
                $this->setor=$rs["setor"];
                $this->cargo=$rs["cargo"];
                $this->empresa=$rs["empresa"];
                $this->isAvaliador=$rs["isAvaliador"];
                $this->isGerencial=$rs["isGerencial"];
                return $this;
            }else{
                return 0;
            }    
        }
    }
    function CadastraFuncionario(ConnBD $conn = null)
    {
        $dados=$this;
        if ($dados==null||$conn==null) {
            return -1;
        } else {
            $senha=sha1('hro123');
            $query="INSERT INTO funcionario(`nome`,`email`,`setor`,`cargo`,`empresa`,`isAvaliador`,`isGerencial`,`senha`) ".
            "VALUES('".$dados->nome."','$dados->email','".strtoupper($dados->setor)."','".strtoupper($dados->cargo)."','".strtoupper($dados->empresa)."','".strtoupper($dados->isAvaliador)."','".strtoupper($dados->isGerencial)."','$senha')";       
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            //return $rs;
            
            if ($rs) {

                return 1;
            }else{

                return 0;
            }
        }
    }
    function AtualizaCadastro(ConnBD $conn = null)
    {
        $dados=$this;
        if ($dados==null||$conn==null) {
            return -1;
        } else {
            $query="UPDATE `funcionario` SET `funcionario`.`cargo`='".strtoupper($dados->cargo)."',`funcionario`.`empresa`='".strtoupper($dados->empresa)."',`funcionario`.`nome`='$dados->nome',`funcionario`.`isAvaliador`='$dados->isAvaliador',`funcionario`.`isGerencial`='$dados->isGerencial' WHERE `funcionario`.`email`='".$dados->email."'";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            
            if ($rs) {
                return 1;
            }else{

                return 0;
            }
        }
    }

    // lista funcionarios presentes no banco
    function ListaFuncionario(ConnBD $conn = null){
         
        if ($conn==null) {
            return -1;
        } else {
            $query="SELECT email,nome from funcionario ORDER BY nome";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            $conn->close();

            while ( $row = $rs->fetch_assoc() ) {
                $funcionarios[] = array('nome'=> $row['nome'],'email'=>$row['email']);

            }
            return $funcionarios;
        }

    }
    // lista avaliadores do banco
    function ListaAvaliador(ConnBD $conn = null){
         $dados=$this;
        if ($dados==null||$conn==null) {
            return -1;
        } else {
            $query="SELECT email,nome from funcionario WHERE isAvaliador=1 ORDER BY nome";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            $conn->close();

            while ( $row = $rs->fetch_assoc() ) {
               $funcionarios[] = array('nome' =>  $row['nome'],'email'=>$row['email']);

            }
            return $funcionarios;
        }

    }
    // verifica se o funcionário é avaliador
    function verificaAvaliador(ConnBD $conn = null){
         if ($conn==null) {
            return -1;
        }
        $avaliado= $this->email;
        $sql= "SELECT * FROM funcionario WHERE email='$avaliado' AND isAvaliador=1";
        $conn->set_query($sql);
        $conn->set_result();
        $r=$conn->get_result();
        $conn->close();
        if(mysqli_num_rows($r)>0){
            return 1;

        } else{ 
            return 0;
        }

    }
    //recupera o avaliador do avaliado  
     function getAvaliador(ConnBD $conn = null){
         $dados=$this;
        if ($dados==null||$conn==null) {
            return -1;
        } else {
            $query="SELECT nome, email FROM funcionario   AS F INNER JOIN avaliado_avaliador AS AA  WHERE AA.avaliado = '$this->email' AND F.email= AA.avaliador;";
            $conn->set_query($query);
            $conn->set_result();
            $rs=$conn->get_result();
            $conn->close();

            if (mysqli_num_rows($rs)>0) {
                $row = $rs->fetch_assoc();
                $funcionarios[] = array('nome' =>  $row['nome'],'email'=>$row['email']);
                return $funcionarios;

            }

            return false;
            
        }

    }

    // lista os funcionários avaliados do avaliador
     function ListaAvaliado(ConnBD $conn = null,$email){
         $dados=$this;
        if ($dados==null||$conn==null) {
            return -1;
        } else {
       
                    $query="SELECT nome, email FROM funcionario   AS F INNER JOIN avaliado_avaliador AS AA  WHERE AA.avaliador = '$email' AND F.email= AA.avaliado ORDER BY F.nome;";
                    $conn->set_query($query);
                    $conn->set_result();
                    $rs=$conn->get_result();
                    $conn->close();
                    while ( $row = $rs->fetch_assoc() ) {
                        $funcionarios[] = array('nome' =>  $row['nome'],'email'=>$row['email']);
                    }
                   
            return $funcionarios;
        }

    }
    // lista setores que o funcionario tem acesso
    function listaSetor(ConnBD $conn = null,$tipo){
        if ($conn==null) {
            return -1;
        } else {
             $setor =array();
            if($tipo==3){              
                $email=$this->email;   
                $query="SELECT nome, idsetor FROM setor  WHERE responsavel = '$email' order by nome; ";
                
            }
            else if($tipo==2){
                 $query="SELECT nome, idsetor FROM setor order by nome; ";

            }
           $conn->set_query($query);
                $conn->set_result();
                $rs=$conn->get_result();
                $conn->close();
                while ( $row = $rs->fetch_assoc() ) {
                     $setor[] = array('nome' =>  $row['nome'],'responsavel'=>$row['idsetor']);
                }
            return $setor;
        }


    }


}
    
?>