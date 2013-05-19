<?php
defined('PDOERROR') or define('PDOERROR',-987654321987654321);
defined('PDOWARNING') or define('PDOWARNING',-987654321123456789);
defined('PDOINFO') or define('PDOINFO', -987654321000000000);
defined('PDOCONFFILE') or define('PDOCONFFILE',$_SERVER['DOCUMENT_ROOT'].'/configuration.inc.php');
/*
    La estructura de este archivo es de este tipo
    $connectPDO_server = 'localhost';
    $connectPDO_port   = '3306';
    $connectPDO_db     = 'db';
    $connectPDO_user   = 'dbuser';
    $connectPDO_pass   = 'dbpass';
*/

class connectPDO extends PDO
{
    var $isTransaction = false;
    protected $preparedSQL=array();
    public function __construct($dsnIN=null,$subdominioPATH=null)
    {
        // Si no existe el archivo de conexion, nada que hacer
        if (!file_exists(PDOCONFFILE)){
            die('No se ha encontrado el archivo de configuracion de la conexion   :('.PDOCONFFILE);
            exit;
        }

        // Partimos diciendo que esto no es transaccional
        $this->isTransaction=false;

        // Incluimos el archivo de configuracion
        require_once(PDOCONFFILE);

        switch ($dsnIN){
            case 'postgres':
                $connectPDO_server = ($connectPDO_server=='')?'localhost':$connectPDO_server;
                $connectPDO_port = ($connectPDO_port=='')?'5432':$connectPDO_port;
                $engine='psql';
                break;

            // Por defecto vamos a asumir que la conexion es a MySQL
            case 'mysql':
            default:
                $connectPDO_server = ($connectPDO_server=='')?'localhost':$connectPDO_server;
                $connectPDO_port = ($connectPDO_port=='')?'3306':$connectPDO_port;
                $engine='mysql';
                break;
        }

        // Verifico que se haya definido la base de datos a donde nos conectaremos, sino hasta aqui no mas llegamos
        if ($connectPDO_db=='')
        {
            die('No se ha definido la base de datos para realizar la conexion... nada que hacer :(');
            exit;
        }

        // Verifico que el usuario se haya definido sino, hasta aqui no mas llegamos
        if ($connectPDO_user=='')
        {
            die('No se ha definido un usuario para realizar la conexion... nada que hacer :(');
            exit;
        }

        // Entonces armor el dsn
        $dsn="{$engine}:dbname={$connectPDO_db};host={$connectPDO_server};port={$connectPDO_port}";

        // Ahora intentamos hacer la conexion
        try
        {
            $con=parent::__construct($dsn, $connectPDO_user, $connectPDO_pass);
        }
        catch(Exception $e)
        {
            echo "No pude conectarme...".$dsn;
            exit;
        }

        //manejo de errores:
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

        $this->exec("SET lc_time_names = 'es_CL'");
        return $con;
    }

    // Aún solo útil funcionando en postgres
    // Def: Entrega las columnas de una tabla
    public function getcolname($tabla,$schema='public'){
        $dataEx=$this->Execute("SELECT column_name as columnas FROM information_schema.columns WHERE table_name ='{$tabla}' AND table_schema = '{$schema}'");
        if($dataEx!=PDOERROR){
            while ($data=$dataEx->fetch()){
                $d[$data['columnas']]=$data['columnas'];
            }

            return $d;
        }
        return PDOERROR;
    }

    public function exec($sql,$param=null){
        if (is_null($param))
        {
            //todo ok
        }
        else{
            //con esto nos protegemos un poco contra SQL Injection.
            $sql=$this->pre_exec($param,$sql);
        }

        $output= parent::exec($sql);
        if ($output===false){
            return PDOERROR;
        }
        return $output;
    }

    public function Execute($sql,$param='no_array',$fetch_mode=PDO::FETCH_ASSOC){
        $sinErrores=true;
        if (!is_array($param))
        {
            //sin consulta preparada lista para SQL injection!!..
            $currentSTH=parent::query($sql,$fetch_mode);
        }else
        {
            //consulta preparada al estilo PDO
            $currentSTH=parent::prepare($sql);
            $currentSTH->setFetchMode($fetch_mode);
            $indice=0;
            foreach ($param as $valor){
                $indice++;
                $currentSTH->bindValue($indice, $valor, self::getPDOType($valor));
            }
            $sinErrores=$currentSTH->execute();
        }

        if ($currentSTH===false || $sinErrores===false){
            //$this->reportarError($sql);
            echo 'Error en: '.$sql;
            return PDOERROR;
        }
        return $currentSTH;
    }

    public function pre_exec($reemplazar,$pajar)
    {
        $pajarA = explode('?',$pajar.'_[EOF]_');
        if (count($pajarA)-1 ==count($reemplazar) )
        {
            foreach($reemplazar as $index => $r)
            {
                if (is_bool($r)){
                    $vn=($r)?'true':'false';
                }
                else if (is_null($r))
                {
                    $vn='NULL';
                }else{
                    $vn=parent::quote($r);
                }
                $NEWpajar .= $pajarA[$index].$vn;
            }
            $index++;
            $NEWpajar .= $pajarA[$index];
            $NEWpajar=substr($NEWpajar,0,-7);
        }else
        {
            $NEWpajar='Error, no coincide la cantidad de parametros especificados con los esperados';
        }
        return $NEWpajar;
    }

    public function getone($sql,$param=null)
    {
        $data=$this->Execute($sql,$param);
        if ($data===PDOERROR)
        {
            $this->reportarError();
            return PDOERROR;
        }
        elseif($data->rowCount()!=1)
        {
            return PDOWARNING;
        }
        return $data->fetchColumn();
    }

    public function getrow($sql,$param=null)
    {
        $data=$this->Execute($sql,$param);
        if ($data===PDOERROR){
            $this->reportarError();
            return PDOERROR;
        }
        elseif($data->rowCount()!=1)
        {
            return PDOWARNING;
        }
        return $data->fetch();
    }

    public function ErrorMsg()
    {
        //documentacion en : http://www.php.net/manual/en/pdostatement.errorinfo.php
        $errores=$this->errorInfo();
        return $errores[2];
    }

    //no es posible volver a hacer un beginTransaction si no he terminado la transaccion anterior
    //entonces debo poder hacer un begin transacion las veces que quiera sin problemas, y debo poder detectar si ya hay uno iniciado

    public function StartTrans()
    {
        //por compotabilidad con adodb
        return $this->beginTransaction();
    }

    public function beginTransaction()
    {
        if ( $this->isTransaction )
        {
            return false;
        }
        else
        {
            $this->isTransaction = parent::beginTransaction();
            return $this->isTransaction;
        }
    }

    public function commit()
    {
        parent::commit();
        $this->isTransaction = false;
    }

    public function rollback()
    {
        parent::rollback();
        $this->isTransaction = false;
    }

    public function hasActiveTransaction()
    {
        return  $this->isTransaction ;
    }

    static private function getPDOType( $in )
    {
        if(is_int($in)) return PDO::PARAM_INT;
        if(is_bool($in)) return PDO::PARAM_BOOL;
        if(is_null($in)) return PDO::PARAM_NULL;

        //valor por defecto
        return PDO::PARAM_STR;
    }
}
?>