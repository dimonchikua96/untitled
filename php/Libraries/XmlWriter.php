<?php

namespace App\Classes\Libraries;

class XmlWriter
{
    /**
     * @var
     */
    var $xml;
    /**
     * @var
     */
    var $indent;
    /**
     * @var array
     */
    var $stack = array();

    /**
     * @param string $indent
     */
    function XmlWriter($indent = '  ')
    {
        $this->indent = $indent;
        $this->xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n\r\n\r\n";
    }

    /**
     *
     */
    function _indent()
    {
        for ($i = 0, $j = count($this->stack); $i < $j; $i++) {
            $this->xml .= $this->indent;
        }
    }

    /**
     * @param $element
     * @param array $attributes
     */
    function push($element, $attributes = array())
    {
        $this->_indent();
        $this->xml .= '<' . $element;
        foreach ($attributes as $key => $value) {
            $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8') . '"';
        }
        $this->xml .= ">\r\n";
        $this->stack[] = $element;
    }

    /**
     * @param $element
     * @param $content
     * @param array $attributes
     */
    function element($element, $content, $attributes = array())
    {
        $this->_indent();
        $this->xml .= '<' . $element;
        foreach ($attributes as $key => $value) {
            $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8') . '"';
        }
        $this->xml .= '>' . str_replace('&nbsp;', ' ', htmlentities($content, ENT_QUOTES, 'UTF-8')) . '</' . htmlentities($element, ENT_QUOTES, 'UTF-8') . '>' . "\r\n";
    }

    /**
     * @param $element
     * @param $content
     * @param array $attributes
     */
    function xml_element($element, $content, $attributes = array())
    {
        $this->_indent();
        $xml = "";
        $xml .= '<' . $element;


        $xml .= '>' . str_replace('&nbsp;', ' ', $content) . ' </' . $element . '>' . "\r\n";

        $xml = str_replace('&lt;', '<', $xml);
        $xml = str_replace('&gt;', '>', $xml);

        $this->xml .= $xml;
    }

    /**
     * @param $element
     * @param array $attributes
     */
    function emptyelement($element, $attributes = array())
    {
        $this->_indent();
        $this->xml .= '<' . $element;
        foreach ($attributes as $key => $value) {
            $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8') . '"';
        }
        $this->xml .= " />\r\n\r\n";
    }

    /**
     *
     */
    function pop()
    {
        $element = array_pop($this->stack);
        $this->_indent();
        $this->xml .= "</$element>\r\n";
    }

    /**
     * @return mixed
     */
    function getXml()
    {
        return $this->xml;
    }

    /**
     *
     */
    function delXml()
    {
        $this->xml = '';
    }
}
