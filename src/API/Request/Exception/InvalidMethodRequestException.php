<?php
namespace Gogs\API\Request\Exception {
    
    /** 
     * Thrown whenever a class that inherits the base-class
     * is used wrong (e.g tries to create on a loaded object)
     *
     * @author Joachim M. Giaever (joachim[]giaever.org)
     */
    class InvalidMethodRequestException extends \Exception {};
}
?>
