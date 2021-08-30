<?PHP
class ConnBD
{
    var $result;
    var $query;
    protected $conn;

    public function __construct() {
        $this->conn=mysqli_connect("localhost","root","","avdesempenho") or die("Erro ao conectar ao Banco de dados");
        mysqli_set_charset($this->conn,"utf8");
    }
    function set_query($new_query)
    {
        $this->query=$new_query;
        //$this->query=mysqli_escape_string($this->conn,$new_query);
    }
    function set_result()
    {
        $this->result=mysqli_query($this->conn,$this->query);
    }
    function get_result()
    {
        return $this->result;
    }
    function close(){
        return mysqli_close($this->conn);
    }
}
?>