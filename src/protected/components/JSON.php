<?php
class JSON extends CJSON {
	/**
	 * Override object encodings to use model __toJSON if available. Additionally
	 * we override array encodings and the nameValue function to scope object
	 * encodings correctly.
	 */
	public static function encode($var)
	{
		switch (gettype($var))
		{
			case 'array':
				if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
						return '{' .
										join(',', array_map(array('JSON', 'nameValue'),
																				array_keys($var),
																				array_values($var)))
										. '}';
				}
				return '[' . join(',', array_map(array('JSON', 'encode'), $var)) . ']';
			case 'object':
				if ($var instanceof CModel && method_exists($var, '__toJSON'))
					return self::encode($var->__toJSON());

				elseif ($var instanceof Traversable)
				{
					$vars = array();
					foreach ($var as $k=>$v)
						$vars[$k] = $v;
				}
				else 
					$vars = get_object_vars($var);

				return '{' .
					join(',', array_map(array('JSON', 'nameValue'),
						array_keys($vars),
						array_values($vars)))
					. '}';
			default:
				return parent::encode($var);
		}
	}

	protected static function nameValue($name, $value)
	{
		return self::encode(strval($name)) . ':' . self::encode($value);
	}
}
