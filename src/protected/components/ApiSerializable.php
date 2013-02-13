<?php

/**
 * Declare an object serializable.
 */
interface ApiSerializable
{
	/**
	 * Provide a custom serialized output.
	 * @return array object properties as an array.
	 */
	public function __toJSON();
}
