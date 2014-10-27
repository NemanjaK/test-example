<?php

class OutputBuilderColors {
    const BLACK = '0;30'; const DARK_GREY = '1;30'; const BLUE = '0;34'; const LIGHT_BLUE = '1;34';
    const GREEN = '0;32'; const LIGHT_GREEN = '1;32'; const CYAN = '0;36'; const LIGHT_CYAN = '1;36';
    const RED = '0;31'; const LIGHT_RED = '1;31'; const PURPLE = '0;35'; const LIGHT_PURPLE = '1;35';
    const BROWN = '0;33'; const YELLOW = '1;33'; const LIGHT_GREY = '0;37'; const WHITE = '1;37';
}

class OutputBuilder {

    private $buffer = "";
    private $color = null;

    private $colorMode = false;

    /** Adds provided string to output buffer */
    public function concat($string)
    {
       if (isset($this->color) && $this->colorMode) {
           $this->buffer .= "\033[" . $this->color ."m";
       }
        $this->buffer .= $string;
    }

    public function clearBuffer() {
        $this->buffer = "";
    }

    /** Sends the buffer to output stream */
    public function flush()
    {
        echo $this->buffer;
        $this->clearBuffer();
    }

    /** Sets the color for following string */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /** Clears color selection */
    public function clearColor()
    {
        if(!empty($this->buffer) && isset($this->color) && $this->colorMode) {
            $this->buffer .= "\033[0m";
        }
       $this->color = null;
    }

    /**
     * @return boolean
     */
    public function isColorMode()
    {
        return $this->colorMode;
    }

    /**
     * @param boolean $colorMode
     * @throws Exception
     */
    public function setColorMode($colorMode)
    {
        if(strpos(PHP_OS, "WIN") !== false && $colorMode === true) {
            throw new Exception("Color mode in Windows is not supported!");
        }
        $this->colorMode = $colorMode;
    }



}