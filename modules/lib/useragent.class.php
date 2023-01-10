<?php
// Useragent Class

class UserAgentFactoryPSec
{
    public static function analyze($string, $imageSize = null, $imagePath = null, $imageExtension = null)
    {
        $class = new UserAgentPSec();
        $imageSize === null || $class->imageSize = $imageSize;
        $imagePath === null || $class->imagePath = $imagePath;
        $imageExtension === null || $class->imageExtension = $imageExtension;
        $class->analyze($string);
        
        return $class;
    }
}

class UserAgentPSec
{
    private $_imagePath = "";
    private $_imageSize = 16;
    private $_imageExtension = ".png";
    
    private $_data = array();
    
    public function __get($param)
    {
        $privateParam = '_' . $param;
        switch ($param) {
            case 'imagePath':
                return $this->_imagePath . $this->_imageSize . '/';
                break;
            default:
                if (isset($this->$privateParam)) {
                    return $this->$privateParam;
                } elseif (isset($this->_data[$param])) {
                    return $this->_data[$param];
                }
                break;
        }
        
        return null;
    }
    
    public function __set($name, $value)
    {
        $trueName = '_' . $name;
        if (isset($this->$trueName)) {
            $this->$trueName = $value;
        }
    }
    
    public function __construct()
    {
        $this->_imagePath = 'img/';
    }
    
    private function _makeImage($dir, $code)
    {
        return $this->imagePath . $dir . '/' . $code . $this->imageExtension;
    }
    
    private function _makePlatform()
    {
        //$this->_data['device']   =& $this->_data['os']; // Custom
        $this->_data['platform'] =& $this->_data['device'];
        if (@$this->_data['device']['title'] != '') {
            $this->_data['platform'] =& $this->_data['device'];
        } elseif (@$this->_data['os']['title'] != '') {
            $this->_data['platform'] =& $this->_data['os'];
        } else {
            $this->_data['platform'] = array(
                "link" => "#",
                "title" => "Unknown",
                "code" => "null",
                "dir" => "browser",
                "type" => "os",
                "image" => $this->_makeImage('browser', 'null')
            );
        }
        
    }
    
    public static function __autoload($className)
    {
        $filePath = dirname(__file__) . '/' . $className . '.php';
        if (is_file($filePath)) {
            require $filePath;
        }
    }
    
    public function analyze($string)
    {
        $this->_data['useragent'] = $string;
        $classList                = array(
            "os",
            "browser"
        );
        foreach ($classList as $value) {
            $class                        = "useragent_detect_" . $value;
            // Not support in PHP 5.2
            //$this->_data[$value] = $class::analyze($string);
            $this->_data[$value]          = call_user_func($class . '::analyze', $string);
            $this->_data[$value]['image'] = $this->_makeImage($value, $this->_data[$value]['code']);
            
        }
        
        // platform
        $this->_makePlatform();
    }
    
}
spl_autoload_register(array(
    'UserAgentPSec',
    '__autoload'
));




This code defines two classes: UserAgentFactoryPSec and UserAgentPSec.

The UserAgentFactoryPSec is a simple factory class that creates instances of UserAgentPSec. 
When invoked, it creates a new instance of UserAgentPSec and calls its analyze() method with the provided 
string and imageSize, imagePath, imageExtension as arguments.

The UserAgentPSec class is used to analyze a user agent string and extract information about the platform, 
device, browser and operating system that the user is using. The class has the following functionality:

It has private properties that store the values for _imagePath, _imageSize and _imageExtension. 
These properties will be used to generate image URLs
It has a private property _data that contains the extracted information from the user agent string
It has a __get() and __set() magic methods to access the private properties of the class.
It has a __construct() method that sets the default value for the _imagePath property.
It has several private helper methods: _makeImage() method which generates an image URL for a specific device, 
os or browser. _makePlatform() method that creates an array for the platform and sets it to the device or 
os depending on which one is available.

A static __autoload() method that autoloads a class files when the class is instantiated.
It has a public analyze() method that is used to analyze the provided user agent string and extract information. 
This method makes use of useragent_detect_os and useragent_detect_browser classes to extract os and browser details 
respectively and then call _makePlatform() to extract the platform details. The method also creates an 'image' 
key for every extracted os and browser that contains the URL for the corresponding device, os or browser.
In overall, this class allows to extract information about a browser and an operating system by analyzing a 
user agent string, and returns these informations as a array