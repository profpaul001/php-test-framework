<?php
namespace Root\PhpTestFramework\Core;

class TestRunner
{
    protected $config;
    protected $results = [];
    
    public function __construct($config)
    {
        if (is_string($config) && file_exists($config)) {
            $this->config = require $config;
        } else {
            $this->config = $config;
        }
    }
    
    public function runTest($testClass, $testMethod, $params = [])
    {
        $result = call_user_func_array([$testClass, $testMethod], $params);
        $this->results[] = [
            'test' => $testClass . '::' . $testMethod,
            'result' => $result
        ];
        return $result;
    }
    
    public function getResults()
    {
        return $this->results;
    }
    
    public function generateReport()
    {
        $html = '<div>';
        
        foreach ($this->results as $result) {
            $success = $result['result']['success'] ?? false;
            $message = $result['result']['message'] ?? '';
            
            $html .= '<div style="margin: 10px 0; padding: 10px; border: 1px solid ' . 
                    ($success ? '#dff0d8' : '#f2dede') . '; background-color: ' . 
                    ($success ? '#dff0d8' : '#f2dede') . '; color: ' . 
                    ($success ? '#3c763d' : '#a94442') . ';">';
            $html .= '<strong>' . $result['test'] . '</strong>: ';
            $html .= $message;
            $html .= '</div>';
        }
        
        $html .= '</div>';
        return $html;
    }
}