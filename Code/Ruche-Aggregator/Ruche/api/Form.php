<?php
    /** fichier		 : api/Form.php
	    description  : Class pour créer les champs des formulaires HTML
	    author       : Philippe SIMIER Lycée Touchard Le Mans
		
	**/

namespace Aggregator\Support;
	
class Form{
	
	/** Methode pour créer un champ input caché (hidden)
	 *
     * @param  string  $name attribut name
	 * @param  string  $value la valeur du champs
     * @return string  le code html   
     */	
	public static function hidden($name, $value){
		return "<input type='hidden' name='{$name}' value='{$value}' />";
	}
	
	/** Methode pour créer un selecteur avec son label
	 *
     * @param  string  $name attribut name
     * @param  array   $list la liste des options
     * @param  string  $label le label associé au selecteur
     * @param  string  $selected l'option séléctionnée par défault	 
     * @return string  le code html   
     */	 
	public static function select($name, $list = array(), $label = null, $selected = null){
		
		$html = array();
		
		foreach ($list as $value => $display)
		{
			if ( $value == $selected)
			
				$html[] = '    <option value="' . $value . '" selected>' . $display . '</option>';
			else
				$html[] = '    <option value="' . $value . '">' . $display . '</option>';	
		}
		
		$list = implode("\n", $html);
		$options = 'name = "'.$name.'" id = "' . $name .'"';
		$options .= 'class = "form-control" '; 
		
		if ($label== null) $label = $name;
		
		$retour  = '<div class="form-group row">'.PHP_EOL;
		$retour .= '    <label class="font-weight-bold col-3 control-label text-right "  for="' . $name . '">'. ucwords($label) ."</label>".PHP_EOL;
		$retour .= '    <div class="col-9">'.PHP_EOL;
		$retour .= "        <select {$options}>{$list}</select>".PHP_EOL;
		$retour .= "    </div>".PHP_EOL;
		$retour .= "</div>".PHP_EOL;
		
		return $retour;
	}
	
	/** Methode pour créer un champ input avec son label
	 *
     * @param  string  $name attribut name
     * @param  string  $type le type du champs
     * @param  string  $label le label associé au champs
	 * @param  string  $value la valeur du champs
     * @return string  le code html   
     */	
	public static function input( $type, $name, $value = null, $options = array(), $label = null ){
	
		if ($label == null) $label = $name;
	    $options['type'] = $type;
		$options['name'] = $name;
		$options['id'] = $name;
		if ($value != null) $options['value'] = $value;
		
		$retour  = '<div class="form-group row">'.PHP_EOL;
		$retour .= '    <label class="font-weight-bold col-3 control-label text-right " for="' . $name . '">'. ucwords($label) ."</label>".PHP_EOL;
		$retour .= '    <div class="col-9">'.PHP_EOL;
		$retour .= '        <input '. Form::attributes($options) .' />'.PHP_EOL;
		$retour .= "    </div>".PHP_EOL;
		$retour .= "</div>".PHP_EOL;
		
		return $retour;	
	}
	
	/** Methode pour créer un champ textarea avec son label
	 *
     * @param  string  $name attribut name
	 * @param  string  $value la valeur du champs
	 * @param  array   $options les attributs du champs
     * @param  string  $label le label associé au champs

     * @return string  le code html   
     */
	public static function textarea($name, $value = null, $options = array(), $label = null ){
	
		if ($label == null) $label = $name;
		$options['name'] = $name;
		$options['id'] = $name;
		
		
		$retour  = '<div class="form-group row">'.PHP_EOL;
		$retour .= '    <label class="font-weight-bold col-3 control-label text-right " for="' . $name . '">'. ucwords($label) ."</label>".PHP_EOL;
		$retour .= '    <div class="col-9">'.PHP_EOL;
		$retour .= '        <textarea '. Form::attributes($options) .' />'.PHP_EOL;
		$retour .= $value;
		$retour .= '</textarea>';
		$retour .= "    </div>".PHP_EOL;
		$retour .= "</div>".PHP_EOL;
		
		return $retour;	
	}	
	
	/**
	 * Methode pour obtenir le code html des attributs à partir d'un tableau
	 *
	 * @param  array  $attributes
	 * @return string
	 */
	public static function attributes($attributes)
	{
		$html = array();

		foreach ((array) $attributes as $key => $value)
		{
			if (is_numeric($key)) $key = $value;
			if ( ! is_null($value)) 
				$element = $key.'="'. $value .'"';
            else
				$element = $key;	
			if ( ! is_null($element)) $html[] = $element;
		}

		return count($html) > 0 ? ' '.implode(' ', $html) : '';
	}



}