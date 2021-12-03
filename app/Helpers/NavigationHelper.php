<?php

/**
 *
 * Set active css class if the specific URI is current URI
 *
 * @param string $path       A specific URI
 * @param string $class_name Css class name, optional
 * @return string            Css class name if it's current URI,
 *                           otherwise - empty string
 */
function setActive(string $path, string $class_name = "is-active")
{
	return Request::path() === $path ? $class_name : "";
}
