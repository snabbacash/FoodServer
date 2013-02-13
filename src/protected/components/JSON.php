<?php
/**
 * A JSON encoder with support for serializable models using the
 * ApiSerializeable interface.
 */
class JSON extends CJSON {
	/**
	 * Encodes an arbitrary variable into JSON format, with support for
	 * serializable objects.
	 *
	 * @param mixed $var any number, boolean, string, array, or object to be
	 * encoded. If var is a string, it will be converted to UTF-8 format first
	 * before being encoded.
	 * @return string JSON string representation of input var
	 */
	public static function encode($var)
	{
		switch (gettype($var))
		{
			case 'array':
				if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
					return '{' .
						// Use our own nameValue so nested objects can be found.
						join(',', array_map(array('JSON', 'nameValue'),
							array_keys($var),
							array_values($var)))
						. '}';
				}
				// Use our own encode so nested objects can be found.
				return '[' . join(',', array_map(array('JSON', 'encode'), $var)) . ']';
			case 'object':
				// Check for serializable objects.
				if ($var instanceof ApiSerializable)
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
					// Use our own nameValue so nested objects can be found.
					join(',', array_map(array('JSON', 'nameValue'),
						array_keys($vars),
						array_values($vars)))
					. '}';
			default:
				return parent::encode($var);
		}
	}

	/**
	 * array-walking function for use in generating JSON-formatted name-value pairs
	 *
	 * @param string $name  name of key to use
	 * @param mixed $value reference to an array element to be encoded
	 *
	 * @return   string  JSON-formatted name-value pair, like '"name":value'
	 * @access   private
	 */
	protected static function nameValue($name, $value)
	{
		return self::encode(strval($name)) . ':' . self::encode($value);
	}
}
