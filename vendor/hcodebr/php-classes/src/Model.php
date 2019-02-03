<?php 

namespace Hcode;


class Model {

	private $values = [];

	/**
         * 
         * @param type $name
         * @param type $args
         * A funcao call ler o metodo que estar sendo chamado pelas classes extendidas
         * ele é capaz de ler o nome da função e seus argumentos.
         * @return retorna o metodo get ou set dinamicamente de acordo com o que foi chamado 
         * pela classe que extende o Model.
         */
        public function __call($name, $args)
	{

		$method = substr($name, 0, 3);
		$fieldName = substr($name, 3, strlen($name));

		switch ($method) {
                    case "get": 
                        /**este método verifica se o atributo chamado pelo metodo get já tem
                         * valor carregado, por exemplo ao cadastrar uma categoria, 
                         * o get pode necessitar de um idcategory que ainda não estava no
                         * banco em caso de atualização 
                         **/
                       return (isset ($this->values[$fieldName]))? $this->values[$fieldName]: NULL;
                    break;
                
                    case "set":
                        $this->values[$fieldName] = $args[0];

                    break;
                }
                

	}
        /**
         * 
         * @param type $data
         * Recebe um objeto de uma classe como array e 
         * de acordo com a chave e valor de cada registro 
         * atibue para a classe que extende este Model,
         */
        public function setData($data = array()) {
            
            foreach ($data as $key => $value) {
                
                $this->{"set".$key}($value);
                
            }
            
        }
        public function getValues() {
            return $this->values;
            
        }

}



 ?>