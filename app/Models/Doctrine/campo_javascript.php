<?php
require_once('campo.php');

use App\Helpers\Doctrine;

class CampoJavascript extends Campo
{
    public $requiere_nombre = true;
    public $requiere_datos = false;
    public $estatico = true;
    public $etiqueta_tamano = 'xxlarge';

    function setTableDefinition()
    {
        parent::setTableDefinition();

        $this->hasColumn('readonly', 'bool', 1, array('default' => 1));
    }

    function setUp()
    {
        parent::setUp();
        $this->setTableName("campo");
    }

    protected function display($modo, $dato, $etapa_id = false)
    {
        if ($etapa_id) {
            $etapa = Doctrine::getTable('Etapa')->find($etapa_id);
            $regla = new Regla($this->etiqueta);
            $etiqueta = $regla->getExpresionParaOutput($etapa->id);
        } else {
            $etiqueta = $this->etiqueta;
        }
        $display = '<div class="form-group">';
        $usuario_backend = App\Models\UsuarioBackend::find(Auth::user()->id);
        if($usuario_backend){
        $display .= '<label class="control-label" for="' . $this->id . '">' . $this->nombre . (!in_array('required', $this->validacion) ? ' ' : '(Opcional)') . '</label>';
        }
        $display .= '<script type="text/javascript">try{' . $etiqueta . '}catch(err){alert(err)}</script>';
        $display .= '</div>';
       
        return $display;
    }

    public function setReadonly($readonly)
    {
        $this->_set('readonly', 1);
    }

   

}