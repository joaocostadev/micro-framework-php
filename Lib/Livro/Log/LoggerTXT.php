<?php
class LoggerTXT extends Logger
{

 public function write($message)
 {
     $text = date('Y-m-d H:i:s') . ' :' . $message;
     $handle = fopen($this->filename, 'a');
     fwrite($handle, $text . "\n");
     fclose($handle);
 }

}
