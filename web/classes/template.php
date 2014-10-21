<?php
class Template
{
    private $vars = "";
    private function output()
    {
		global $core;
        $this->vars["global"] = array('menu' => $GLOBALS['menu'], 'auth' => $core->get_user(), 'base' => $GLOBALS['base'], '_GET' => $GLOBALS['_GET']);
        echo $this->template->render($this->vars);
    }

    public function render($tmpl, $vars = array())
    {
        global $twig;
        $this->vars = $vars;
        $this->tmpl = $tmpl;

        $this->template = $twig->loadTemplate($this->tmpl.'.twig');

        //echo $template->render(array('y' => date("Y"), 'h1' => 'world', 'content' => 'some text'));
        // $this->load_lang_strings();

        $this->output();
    }

}
?>
