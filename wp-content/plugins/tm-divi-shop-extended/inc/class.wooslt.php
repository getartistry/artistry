<?php
    
    if ( ! defined( 'ABSPATH' ) ) { exit;}
    
    class WOO_SLT
        {
            var $licence;
            
            var $interface;
            
            /**
            * 
            * Run on class construct
            * 
            */
            function __construct( ) 
                {
                    $this->licence              =   new WOO_SLT_licence(); 

                    $this->interface            =   new WOO_SLT_options_interface();
                     
                }
                
              
        } 
    
    
    
?>