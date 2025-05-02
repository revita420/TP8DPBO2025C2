<?php
class Template {
    private $template;
    private $vars = array();

    public function __construct($template) {
        $this->template = TEMPLATE_PATH . '/' . $template;
    }

    // Set a template variable
    public function set($key, $value) {
        $this->vars[$key] = $value;
    }

    // Render the template with variables
    public function render() {
        // Check if template exists
        if (!file_exists($this->template)) {
            throw new Exception("Template {$this->template} not found.");
        }

        // Extract variables for template
        extract($this->vars);

        // Start output buffering
        ob_start();

        // Include the template file
        include($this->template);

        // Get the content and clean the buffer
        $content = ob_get_clean();

        return $content;
    }

    // Utility method: Render sub-template
    public function renderPartial($template, $vars = array()) {
        $fullPath = TEMPLATE_PATH . '/' . $template;
        
        if (!file_exists($fullPath)) {
            throw new Exception("Template {$fullPath} not found.");
        }

        // Extract variables for template
        extract($vars);

        // Start output buffering
        ob_start();

        // Include the template file
        include($fullPath);

        // Get the content and clean the buffer
        return ob_get_clean();
    }
}